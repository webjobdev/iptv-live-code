<?php

/**
 * Transcoder Job Status Scheduler
 *
 * @name TranscoderJobStatusScheduler
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */

namespace Contus\Video\Schedulers;

use Contus\Base\Schedulers\Scheduler;
use Aws\ElasticTranscoder\ElasticTranscoderClient;
use Contus\Video\Models\Video;
use Aws\S3\S3Client;
use Contus\Video\Models\TranscodedVideo;
use Contus\Video\Models\VideoPreset;
use Illuminate\Support\Facades\Storage;
use Contus\Video\Repositories\AWSUploadRepository;
use Carbon\Carbon;
use Contus\Video\Models\Ffmpegstatus;
use Contus\Video\Helpers\FfmpegHandler;
use Contus\Video\Repositories\FfmpegVideoRepository;
use Psy\Exception\FatalErrorException;
use Contus\Notification\Repositories\NotificationRepository;

use App\Support\GenerateSpriteImage;

class TranscoderJobStatusScheduler extends Scheduler
{
    /**
     * Class property to hold Video instance
     *
     * @var \Contus\Video\Models\Video
     */
    protected $video = null;
    protected $awsRepository = null;

    /**
     * Class intializer
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->video = new Video();
        $this->awsRepository = new AWSUploadRepository(new TranscodedVideo(), new VideoPreset());
        $this->ffmpeg = new FfmpegVideoRepository();
    }

    /**
     * Scheduler frequency
     *
     * @param \Illuminate\Console\Scheduling\Event $event
     * @return void
     */
    public function frequency(\Illuminate\Console\Scheduling\Event $event)
    {
        $event->everyMinute();
    }

    /**
     * Scheduler call method
     * actual execution go's here
     *
     * @return \Closure
     */
    public function call()
    {
        return function () {
            /**
             * In this function, all the unfinished jobs are retrieved from the database.
             * Their status are checked with AWS and updated in the database.
             */

            $unfinishedJobs = Video::where('job_status', 'Uploaded')->where('is_archived', '!=', 1)->get();
            $transcodeType = env('VIDEO_TRANSCODE_TYPE');
            if ($transcodeType == 'FFMPEG') {
                \Log::info('FFMPEG is started');
                $single = Video::where('job_status', 'Uploaded')->where('is_archived', '!=', 1)->first(); //->has('categories')
                $ffmpegStatus = Ffmpegstatus::get()->first();
                if ($ffmpegStatus->status && $single) {
                    $videoModel = $single;
                    $videoObj = new Video();
                    $randomFileDir = rand(5, 15) . date('m-d-Y_hia');
                    $ffmpeg = new FfmpegHandler($videoModel->fine_uploader_name, $videoModel->fine_uploader_uuid);
                    $this->ffmpeg->changeFfmpegStatus(0);
                    try {
                        $newName = $ffmpeg->generateNewName();
                        $videoModel->job_status = 'Progressing';
                        $videoModel->fine_uploader_name = $newName;
                        $videoModel->save();
                        $ffmpeg->prepareTranscode();
                        if ($ffmpeg->transcode()) {
                            $this->ffmpeg->uploadFilesToS3($ffmpeg->destinationFolder, $videoModel, $randomFileDir);
                            // $videoModel->is_active = 1;
                            $videoModel->upload_percentage = 100;
                            $videoModel->slug = $videoObj->generateDynamicSlug($videoModel);
                            $videoModel->save();

                            if($videoModel->is_notify) {
                                $notifyObj = new NotificationRepository();
                                $notifyObj->notify('video', $videoModel->id);
                            }

                            $this->ffmpeg->changeFfmpegStatus(1);
                            $ffmpeg->clearFolders();
                        } else {
                            throw new FatalErrorException("Error in transcoder");
                        }
                    } catch (\Exception $exception) {
                        $videoModel->job_status = 'Error';
                        $videoModel->transcode_status = 'Error';
                        $videoModel->save();
                        $this->ffmpeg->changeFfmpegStatus(1);
                        $ffmpeg->clearFolders();
                        app('log')->error(' ###File : ' . $exception->getFile() . ' ##Line : ' . $exception->getLine() . ' #Error : ' . $exception->getMessage());
                        echo $exception->getMessage();
                        exit;
                    }
                    $videoNotify = $this->video->findorfail($single->id);
                }
            } else if($transcodeType =='TAAS') { //TAAS Transcode Process
               
                $unfinishedJobs = Video::where('job_status', '!=', 'Complete')->where('job_status', '!=', 'Error')->where('job_status', '!=', 'Uploading')->where('job_id', '!=', '')->get(); //->has('categories')
                foreach ($unfinishedJobs as $unfinishedJob) {
                    try {
                       
                        $client = new \GuzzleHttp\Client();
                        $taas_staus_url = env('TAAS_STATUS_URL');
                        $response = $client->get($taas_staus_url.$unfinishedJob ['job_id']);
                        $response = $response->getBody()->getContents();
                        $result = json_decode($response);
                        if ($result) {
                            $percent = 0;
                            if($result->status !='Error'){
                                $trackResult = $result->result;
                                $percent =  $trackResult[0]->total_percentage;
                                $jobStatus = $trackResult[0]->status;
                                if($percent == 100){
                                    $jobStatus ='Complete';
                                }else{
                                    $jobStatus ='Processing';
                                }
                            }else{
                                $jobStatus ='Error';
                            }   
                            /**
                             * Update job status in the database.
                             */
                            $this->video    = new Video();
                            $videoInstance  = $this->video->findorfail($unfinishedJob ['id']);
                            $newSlug        = $this->video->generateDynamicSlug($videoInstance);

                            Video::where('id', $unfinishedJob['id'])->update([
                                'slug' => $newSlug,                             
                                'upload_percentage' => $percent,
                            ]);
                          
                            if(($jobStatus == 'Error') && $videoInstance) {
                              Video::where('id', $unfinishedJob['id'])->update([
                                    'transcode_status' => 'Error',
                                    'job_status' => 'Error',
                                ]);
                            }
                           
                            /**
                             * Delete the fine uploader file in the server if the job status is Complete.
                             */
                            if (($jobStatus == "Complete") && $videoInstance) {
                                $this->video    = new Video();
                                $videoInstance  = $this->video->findorfail($unfinishedJob ['id']);
                                $videoInstance->sprite_image_status = 0;
                                $videoInstance->job_status = $jobStatus;
                                $videoInstance->transcode_status = 'Complete';
                                $videoInstance->save();
                                
                                /** Call to method to apply encryption technique on transcoded videos */
                              
                                $s3Client = S3Client::factory(array(
                                    'region' => env('AWS_REGION'),
                                    'version' => env('AWS_VERSION'),
                                    'credentials' => [
                                        'key' => env('AWS_KEY'),
                                        'secret' => env('AWS_SECRET')
                                    ]));
                                $awsS3Bucket = env('AWS_BUCKET');
                                $filePath = base_path('public' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'videos' . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . $videoInstance->fine_uploader_uuid . DIRECTORY_SEPARATOR . $videoInstance->fine_uploader_name);
                                $folderPath = base_path('public' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'videos' . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . $videoInstance->fine_uploader_uuid);
                                if (file_exists($filePath)) {
                                    unlink($filePath);
                                }
                                if (file_exists($folderPath)) {
                                   rmdir($folderPath);
                                }
                            }
                        }
                    } catch (\Exception $exception) {
                        app('log')->error(' ###File : ' . $exception->getFile() . ' ##Line : ' . $exception->getLine() . ' #Error : ' . $exception->getMessage());
                    }
                }
            }else {
                $unfinishedJobs = Video::where('job_status', '!=', 'Complete')->where('job_status', '!=', 'Error')->where('job_status', '!=', 'Uploading')->where('job_id', '!=', '')->get(); //->has('categories')
                foreach ($unfinishedJobs as $unfinishedJob) {
                    try {
                        $client = ElasticTranscoderClient::factory(array(
                            'region' => env('AWS_REGION'),
                            'version' => env('AWS_VERSION'),
                            'credentials' => [
                                'key' => env('AWS_KEY'),
                                'secret' => env('AWS_SECRET')
                            ]));
                        $result = $client->readJob(array('Id' => $unfinishedJob ['job_id']));
                        if ($result ['Job']) {
                            $jobStatus = $result ['Job'] ['Status'];

                            $percent = $this->awsRepository->getAWSProgressPercent($result['Job']);

                                                       
                            /**
                             * Update job status in the database.
                             */
                            $this->video    = new Video();
                            $videoInstance  = $this->video->findorfail($unfinishedJob ['id']);
                            $newSlug        = $this->video->generateDynamicSlug($videoInstance);

                            Video::where('id', $unfinishedJob['id'])->update([
                                'slug' => $newSlug,                             
                                'upload_percentage' => $percent,
                            ]);

                            if(($jobStatus == 'Error') && $videoInstance) {
                                Video::where('id', $unfinishedJob['id'])->update([
                                    'transcode_status' => 'Error',
                                ]);
                            }


                            /**
                             * Delete the fine uploader file in the server if the job status is Complete.
                             */
                            if (($jobStatus == "Complete")&& $videoInstance) {
                                $this->video    = new Video();
                                $videoInstance  = $this->video->findorfail($unfinishedJob ['id']);
                                $videoInstance->sprite_image_status = 0;
                                $videoInstance->job_status = $jobStatus;
                                $videoInstance->save();
                                
                                /** Call to method to apply encryption technique on transcoded videos */
                                $this->applyEncryptionTechnique($result);
                                
                                if($videoInstance->is_notify) {
                                    $notifyObj = new NotificationRepository();
                                    $notifyObj->notify('video', $videoInstance->id);
                                }
                                $s3Client = S3Client::factory(array(
                                    'region' => env('AWS_REGION'),
                                    'version' => env('AWS_VERSION'),
                                    'credentials' => [
                                        'key' => env('AWS_KEY'),
                                        'secret' => env('AWS_SECRET')
                                    ]));
                                $awsS3Bucket = env('AWS_BUCKET');
                                $filePath = base_path('public' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'videos' . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . $videoInstance->fine_uploader_uuid . DIRECTORY_SEPARATOR . $videoInstance->fine_uploader_name);
                                $folderPath = base_path('public' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'videos' . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . $videoInstance->fine_uploader_uuid);
                                if (file_exists($filePath)) {
                                    unlink($filePath);
                                }
                                if (file_exists($folderPath)) {
                                   rmdir($folderPath);
                                }
                            }
                        }
                    } catch (\Exception $exception) {
                        app('log')->error(' ###File : ' . $exception->getFile() . ' ##Line : ' . $exception->getLine() . ' #Error : ' . $exception->getMessage());
                    }
                }
            }
        };
    }

    /**
     * Function to save the thumburl if hls
     *
     * @param array $objects
     * @param string $awsRegion
     * @param string $awsS3Bucket
     * @param array $videoInstance
     */
    public function save_thumb_hls($objects, $awsRegion, $awsS3Bucket, $videoInstance)
    {
        foreach ($objects ['Contents'] as $thumb) {
            $transcodedThumb = new TranscodedVideo();
            $transcodedThumb->video_id = $videoInstance->id;
            $transcodedThumb->thumb_url = 'https://s3.' . $awsRegion . '.amazonaws.com/' . $awsS3Bucket . '/' . $thumb ['Key'];
            $transcodedThumb->is_active = 1;
            $transcodedThumb->save();
        }
    }
    /*
    * Method to apply enctyption technique on transcoded videos
    *
    * @param array $transcodeResult
    * @return void
    */
    public function applyEncryptionTechnique($transcodeResult){
        $aws_prefix = trim($transcodeResult['Job']['OutputKeyPrefix'],'/');
        if (strpos($aws_prefix, 'output') !== false || strpos($aws_prefix, 'FFMPEG') !== false) {
            $prefix = $aws_prefix;
            $key = $prefix . '/playlist.m3u8';
            $result = $this->awsRepository->fetchFileFromS3Bucket($key);
            $playlist = (string) $result['Body'];
            $playlistExplode = explode("\n", $playlist);
            $formats = [];
            foreach ($playlistExplode as $playlistLine) {
                if (strpos($playlistLine, '.m3u8') !== false) {
                    $formats[] = $playlistLine;
                }
            }
            foreach ($formats as $format) {
                $formatResult = $this->awsRepository->fetchFileFromS3Bucket($prefix . '/' . $format);
                $formatResultBody = (string) $formatResult['Body'];
                if (strpos($aws_prefix, 'output') !== false) {
                    $replaceableText = str_replace("m3u8", 'key', $format);
                } else {
                    $replaceableText = "enc.key";
                }
                $formatResultBody = str_replace($replaceableText, env('API_URL') . 'api/v2/key?key=' . $prefix . '/' . $format, $formatResultBody);
                $s3Client = $this->awsRepository->awsS3Client;
                $awsS3Bucket =  env('AWS_BUCKET');
                $s3Client->putObject(array(
                    'Bucket' => $awsS3Bucket,
                    'Key' => $prefix . '/' . $format,
                    'Body' => $formatResultBody,
                    'ACL' => 'public-read',
                    'ServerSideEncryption' => 'AES256',
                ));
                echo "Success : $prefix/$format \t\n";
            }
        }
    }
}
