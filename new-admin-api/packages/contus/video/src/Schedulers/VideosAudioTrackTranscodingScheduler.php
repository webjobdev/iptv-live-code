<?php
/**
 * Transcode video lingual audio tracks
 *
 * @name VideosAudioTrackTranscodingScheduler
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2019 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Schedulers;

use Contus\Base\Schedulers\Scheduler;
use Contus\Video\Models\VideoAudioUploads;
use Contus\Video\Models\Video;
use Contus\Video\Repositories\AWSUploadRepository;
use Contus\Video\Models\TranscodedVideo;
use Contus\Video\Models\VideoPreset;
use Aws\ElasticTranscoder\ElasticTranscoderClient;
use Contus\Video\Schedulers\TranscoderJobStatusScheduler;
use Contus\Video\Models\Ffmpegstatus;
use Exception;

class VideosAudioTrackTranscodingScheduler extends Scheduler{
    /**
     * Class intializer
     *
     * @return void
     */
    public function __construct(){
        parent::__construct();
        $this->video = new Video();
        $this->audioTracksModel = new VideoAudioUploads();
        $this->awsRepository = new AWSUploadRepository(new TranscodedVideo(), new VideoPreset());
        $this->audioTrackS3SourcePath = config("contus.base.media.video_lingual_audio_tracks.s3_location_audio_source");
        $this->AWSPipelineId = env('AWS_PIPELINE_ID');
        $this->audioAWSPresetID = env('VIDEO_LINGUAL_AUDIO_PRESET_ID');
        $this->awsBucketURL = env('AWS_BUCKET_URL');
        $this->AWSConfigurations = array(
            'region' => env('AWS_REGION'),
            'version' => env('AWS_VERSION'),
            'credentials' => [
                'key' => env('AWS_KEY'),
                'secret' => env('AWS_SECRET')
            ]
        );
        $this->AWSBucket = env('AWS_BUCKET');
    }
    /**
     * Scheduler frequency
     *
     * @param \Illuminate\Console\Scheduling\Event $event
     * @return void
     */
    public function frequency(\Illuminate\Console\Scheduling\Event $event){
        $event->everyMinute();
    }
    /**
     * Scheduler call method
     * actual execution go's here
     *
     * @return \Closure
     */
    public function call(){
        return function () {
            $this->performTranscoding();
            $this->addAudioTrackIntoVideosHLSFile();
        };
    }
    /**
     * Method to perform transcoding of audio tracks
     * 
     * @return void
     */
    public function performTranscoding(){
        $jobLists = $this->audioTracksModel
                            ->select('id', 'video_id', 'audio_title', 'audio_src_url')
                            ->where('job_status' ,'Audio Uploaded')
                            ->whereIn('video_id', function($query){
                                $query->select('id')->where('job_status','Complete')->from('videos');
                            })
                            ->where('audio_src_url','!=','')->limit(1)->get();
        $ffmpegStatus = Ffmpegstatus::where('status', 1)->where('format',2)->get()->first();
        \Log::info('audio-tra-1');
        if(!is_null($ffmpegStatus)) {
            \Log::info('audio-tra-1.5');
            foreach($jobLists as $job){
                \Log::info('audio-tra-2');
                try {
                    $video = Video::find($job['video_id']);
                    if($video->job_status == 'Complete') {
                        $this->transcodeAudioFiles($job['audio_src_url'], $job['video_id'], $job['id'], $job['id'], $ffmpegStatus->id);
                    }
                } catch (Exception $exception){
                    app('log')->error(' ###File : ' . $exception->getFile() . ' ##Line : ' . $exception->getLine() . ' #Error : ' . $exception->getMessage());
                }
            }
        }
    }
    /**
     * Method to transcode the audio tracks
     * 
     * @param string $sourceFile
     * @param int $videoID
     * @param string $audioTitle
     * @param int $audioTrackID
     * 
     * @return void
     */
    public function transcodeAudioFiles($sourceFilePath, $videoID, $audioTitle, $audioTrackID, $ffmpegStatusId){
        Ffmpegstatus::where('id',$ffmpegStatusId)->update(['status' => 0]);
        \Log::info('audio-tra-3');
        $destinationFilePath = $videoAWSPrefix = $jobID = $hlsPlaylistURL = $videoJobStatus = $videoAWSPrefix = '';
        $outputKey = $outputs = $playlistConfig = $presets = $columns = array();
        $columns = ['aws_prefix','job_status'];
        $videoData = $this->obtainVideoData($videoID, $columns);
        $videoJobStatus = $videoData->job_status;
        $videoAWSPrefix = $videoData->aws_prefix;
        if($videoJobStatus == 'Complete' && $videoAWSPrefix != ""){
            $client = $this->awsRepository->awsETClient;
            $sourceFileName = $this->getFilename($sourceFilePath);
            $destinationFileName = $videoID.'-'.time().'-'.rand(10,100);
            $sourceFilenameWithEXT = $this->getFilename($sourceFilePath, true);
            $audioSourceFolder = $this->audioTrackS3SourcePath.$sourceFilenameWithEXT;
            $destinationFilePath = $videoAWSPrefix. '/'. 'audios'. '/'. $audioTitle . '/'. $destinationFileName;
            $audioTrackSourcePath = parse_url(urldecode($sourceFilePath))['path'];
            $audioOutputKey = 'audio-' . $this->audioAWSPresetID;
            $bucketAudioSourcePath = env('MINIO_BUCKET_PATH').'/'.$audioTrackSourcePath;
            $bucketAudioDestinationPath = env('MINIO_BUCKET_PATH').'/'. env('AWS_BUCKET').'/'.$destinationFilePath;
            \Log::info($bucketAudioDestinationPath);
            if (!is_dir($bucketAudioDestinationPath)) {
                mkdir($bucketAudioDestinationPath, 0777, true);
                chmod($bucketAudioDestinationPath, 0777);
            }
            $ffmpegCommand = '/opt/kaltura/bin/ffmpeg -i '.$bucketAudioSourcePath.' -vn -ac 2 -acodec aac -crf 20 -sc_threshold 0 -hls_list_size 0 -hls_time 4  -hls_segment_filename '.$bucketAudioDestinationPath.'/audio_%03d.ts '.$bucketAudioDestinationPath.'/playlist.m3u8';

            $output = shell_exec($ffmpegCommand. "  2>&1; echo $?");
            \Log::info($output);
            $output = explode(PHP_EOL, $output);

            $hlsPlaylistURL = $this->awsBucketURL. $destinationFilePath. '/'. 'playlist'. '.m3u8';
            $audioTracksModel = $this->audioTracksModel->findorfail($audioTrackID);
            $audioTracksModel->audio_hls_url = $hlsPlaylistURL;
            $audioTracksModel->pipeline_id = $this->AWSPipelineId;
            $audioTracksModel->audio_hls_prefix = 'audios'. '/'. $audioTitle . '/'. $destinationFileName;
            $audioTracksModel->job_id = $jobID;
            $audioTracksModel->job_status = 'Complete';
            $audioTracksModel->save();
            Ffmpegstatus::where('id',$ffmpegStatusId)->update(['status' => 1]);
            \Log::info('audio-tra-4');
        } else {
            Ffmpegstatus::where('id',$ffmpegStatusId)->update(['status' => 1]);
            \Log::info('audio-tra-3.5');

        }
    }
    /**
     * Method to get the file name form the audio source
     * 
     * @param string $sourceFile
     * @return string
     */
    public function getFilename($sourceFile, $withEXT = null){
        $splitURL = explode('/',$sourceFile);
        $filenameArr = ($withEXT) ? end($splitURL) : (explode('.',end($splitURL)));
        return ($withEXT) ? $filenameArr : $filenameArr[0];
    }
    /**
     * Method to obtain videos data for a column
     * 
     * @param int $videoID
     * @param array $columns
     * @return string
     */
    public function obtainVideoData($videoID, $columns){
        return $this->video->select($columns)->where('id',$videoID)->first();
    }

    /**
     * Method to include the lingual track files into the video's main hls file
     * 
     * @return void
     */
    public function addAudioTrackIntoVideosHLSFile(){
        $unfinishedAudioJobs = $this->audioTracksModel
        ->where('job_status', '=', 'Complete')
        ->where('audio_hls_prefix', '!=', '')
        ->where('video_hls_update_status', '=', 0)->get();
        if($unfinishedAudioJobs){
            foreach($unfinishedAudioJobs as $unfinishedAudioJob){
                \Log::info('audio-tra-5');
                    $videoDetails= $this->video->where('id','=',$unfinishedAudioJob->video_id)->first();
                    $this->applyAudioDetails($unfinishedAudioJob,$videoDetails->aws_prefix, $videoDetails->hls_playlist_url, $unfinishedAudioJob->video_id);
            }
        }    
    }
    /**
     * Method to update audio data into the video's playlist file
     * 
     * @param array $transcodeResult
     * @param string $key
     * @param string $videoHLSURL
     * @param int $videoIDs
     * 
     * @return void
     */
    public function applyAudioDetails($transcodeResult, $key, $videoHLSURL, $videoID){
        $isFileMoved = false;
        $audioPresetFiles = array();
        $videoCurrentFilename = $this->getFilename($videoHLSURL, true);
        $videoHLSFile = $key . '/' . $videoCurrentFilename;
        $result = $this->awsRepository->fetchFileFromS3Bucket($videoHLSFile);
        $playlist = (string) $result['Body'];
        $search= '#EXT-X-VERSION:3';
        $audioTrackMainURI = $key. '/'. $transcodeResult->audio_hls_prefix. '/'. 'playlist.m3u8';
        $audioMainHLSContent = $this->awsRepository->fetchFileFromS3Bucket($audioTrackMainURI);
        if(!$audioMainHLSContent) {
            return true;
        }
        
        // Set Default audio track
        if(strpos($playlist, 'DEFAULT=YES') === false){
            $defaultAudioTrackStr = $search. "\n". '#EXT-X-MEDIA:TYPE=AUDIO,GROUP-ID="stereo",LANGUAGE="ta",NAME="Auto",DEFAULT=YES,AUTOSELECT=YES';
            $formatResultBody = str_replace($search, $defaultAudioTrackStr, $playlist);
        } else {
            $formatResultBody = $playlist;
        }
        
        $audioTrackPresetURI = $transcodeResult->audio_hls_prefix. '/playlist.m3u8';
        $insert = '#EXT-X-MEDIA:TYPE=AUDIO,GROUP-ID="stereo",LANGUAGE="ta",NAME="'.$transcodeResult->audio_title.'",DEFAULT=NO,AUTOSELECT=NO,URI="'.$audioTrackPresetURI.'"';
        $replace = $search. "\n". $insert;
        $formatResultBody = str_replace($search, $replace, $formatResultBody);
        
        // Add AUDIO tag to each of the video preset files
        $searchTxt = '#EXT-X-STREAM-INF:';
        $replaceTxt = '#EXT-X-STREAM-INF:'.'AUDIO="stereo",';
        if(strpos($formatResultBody, 'AUDIO="stereo"') === false){
            $formatResultBody = str_replace($searchTxt, $replaceTxt, $formatResultBody);
        }
        $videoNewHLSFilename = $key . '/' .$this->obtainNewFilenameVideo($videoID, $videoHLSURL). '.m3u8';
        $s3Client = $this->awsRepository->awsS3Client;
        try {
            $isFileMoved = $s3Client->putObject(array(
                'Bucket' => $this->AWSBucket,
                'Key' => $videoNewHLSFilename,
                'Body' => $formatResultBody,
                'ACL' => 'public-read',
                // 'ServerSideEncryption' => 'AES256',
            ));
            if($isFileMoved){
                $this->deleteExistingVideoHLSFile($videoHLSFile, $videoNewHLSFilename, $videoID);
            }
        } catch (Exception $exception){
            app('log')->error(' ###File : ' . $exception->getFile() . ' ##Line : ' . $exception->getLine() . ' #Error : ' . $exception->getMessage());
        }
        $this->audioTracksModel::where('id','=',$transcodeResult->id)->update(['video_hls_update_status'=>1]);
    }
    /**
     * Method to create new name for video playlist file,
     * to clear AWS Cloudfront cache
     * 
     * @param int $videoID
     * @param string $videoHLSURL
     * 
     * @return string
     */
    public function obtainNewFilenameVideo($videoID, $videoHLSURL){
        $videoCurrentFilename = $this->getFilename($videoHLSURL);
        $splitFilename =  explode('_', $videoCurrentFilename);
        $count = (isset($splitFilename[1])) ? ($splitFilename[1] + 1) : 1;
        return ($count) ? $splitFilename[0] . '_' . ($count) : $videoCurrentFilename;
    }
    /**
     * Method to delete the videos existing HLS file and updated the same in collection
     * 
     * @param string $videoHLSFile
     * @param string $videoNewHLSFilename
     * @param int $videoID
     * 
     * @return void
     */
    public function deleteExistingVideoHLSFile($videoHLSFile, $videoNewHLSFilename, $videoID){
        $isDeleted = false;
        try{
            $isDeleted = $this->awsRepository->deleteFileFromS3Bucket($videoHLSFile);
            if($isDeleted){
                $reformedVideoURL = $this->awsBucketURL. $videoNewHLSFilename;
                $this->video->where('id','=',$videoID)->update(['hls_playlist_url' => $reformedVideoURL]);
            }
            \Log::info('audio-tra-5-'.$videoID);
        } catch (Exception $exception){
            app('log')->error(' ###File : ' . $exception->getFile() . ' ##Line : ' . $exception->getLine() . ' #Error : ' . $exception->getMessage());
        }
    }
}