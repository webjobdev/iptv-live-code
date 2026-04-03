<?php
/**
 * Transcode trailer video tracks
 *
 * @name VideosTrailerTranscodingScheduler
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2019 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Schedulers;

use Aws\ElasticTranscoder\ElasticTranscoderClient;
use Contus\Base\Schedulers\Scheduler;
use Contus\Video\Models\TranscodedVideo;
use Contus\Video\Models\Video;
use Contus\Video\Models\VideoPreset;
use Contus\Video\Repositories\AWSUploadRepository;
use Contus\Video\Schedulers\TranscoderJobStatusScheduler;
use Exception;

class VideosTrailerTranscodingScheduler extends Scheduler
{
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
        $this->videoTrailerS3SourcePath = config("contus.base.media.video_trailer.s3_location_trailer_source");
        $this->AWSPipelineId = env('AWS_PIPELINE_ID');
        $this->trailerVideoAWSPresetID = env('TRAILER_VIDEO_PRESET_ID');
        $this->awsBucketURL = env('AWS_BUCKET_URL');
        $this->AWSConfigurations = array(
            'region' => env('AWS_REGION'),
            'version' => env('AWS_VERSION'),
            'credentials' => [
                'key' => env('AWS_KEY'),
                'secret' => env('AWS_SECRET'),
            ],
        );
        $this->AWSBucket = env('AWS_BUCKET');
        $this->transcoderScheduler = new TranscoderJobStatusScheduler();
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
            $this->performTranscoding();
            $this->trackTranscoding();
        };
    }
    /**
     * Method to perform transcoding of audio tracks
     *
     * @return void
     */
    public function performTranscoding()
    {
        $jobLists = $this->video->select('id', 'trailer_url', 'title')->where('trailer_status', 'Trailer Uploaded')->where('trailer_url', '!=', '')->get();
        foreach ($jobLists as $job) {
            try {
                $this->transcodeTrailerFiles($job['trailer_url'], $job['id'], $job['title']);
            } catch (Exception $exception) {
                app('log')->error(' ###File : ' . $exception->getFile() . ' ##Line : ' . $exception->getLine() . ' #Error : ' . $exception->getMessage());
            }
        }
    }
    /**
     * Method to transcode the audio tracks
     *
     * @param string $sourceFile
     * @param int $videoID
     * @param string $videoTitle
     *
     * @return void
     */
    public function transcodeTrailerFiles($sourceFilePath, $videoID, $videoTitle)
    {
        $destinationFilePath = $videoAWSPrefix = $jobID = $hlsPlaylistURL = $videoJobStatus = $videoAWSPrefix = '';
        $outputKey = $outputs = $playlistConfig = $presets = $columns = array();
        $columns = ['aws_prefix', 'job_status'];
        $videoData = $this->obtainVideoData($videoID, $columns);
        $videoJobStatus = $videoData->job_status;
        $videoAWSPrefix = $videoData->aws_prefix;
        if ($videoAWSPrefix != "") {
            $client = $this->awsRepository->awsETClient;
            $sourceFileName = $this->getFilename($sourceFilePath);
            $sourceFilenameWithEXT = $this->getFilename($sourceFilePath, true);
            $trailerSourceFolder = $this->videoTrailerS3SourcePath . $sourceFilenameWithEXT;
            $destinationFilePath = $videoAWSPrefix . '/' . 'trailer' . '/' . $videoTitle . '/' . $sourceFileName;
            $trailerOutputKey = 'trailer-' . $this->trailerVideoAWSPresetID;
            $presets = explode(',', $this->trailerVideoAWSPresetID);
            foreach ($presets as $presetID) {
                $trailerKey = 'audio-' . $presetID;
                $output = array('Key' => $trailerKey, 'Rotate' => 'auto', 'PresetId' => $presetID);
                $output['SegmentDuration'] = '5';
                $outputKey[] = $trailerKey;
                $outputs[] = $output;
            }
            $playlistConfig = [[
                'Name' => 'playlist',
                'Format' => 'HLSv3',
                'OutputKeys' => $outputKey,
                'HlsContentProtection' => ['Method' => 'aes-128', 'KeyStoragePolicy' => 'WithVariantPlaylists'],
            ]];
            $createJob = $client->createJob(
                array(
                    'PipelineId' => $this->AWSPipelineId,
                    'Input' => array('Key' => $trailerSourceFolder),
                    'Outputs' => $outputs,
                    'OutputKeyPrefix' => $destinationFilePath . '/',
                    'Playlists' => $playlistConfig,
                )
            );
            if ($createJob['Job']) {
                $jobID = $createJob['Job']['Id'];
                $hlsPlaylistURL = $this->awsBucketURL . $destinationFilePath . '/' . 'playlist' . '.m3u8';
                $videoModel = $this->video->findorfail($videoID);
                $videoModel->trailer_hls_url = $hlsPlaylistURL;
                $videoModel->trailer_hls_prefix = 'trailer' . '/' . $videoTitle . '/' . $sourceFileName;
                $videoModel->trailer_jobid = $jobID;
                $videoModel->trailer_status = 'Progressing';
                $videoModel->save();
            }
        }
    }
    /**
     * Method to get the file name form the trailer source
     *
     * @param string $sourceFile
     * @return string
     */
    public function getFilename($sourceFile, $withEXT = null)
    {
        $splitURL = explode('/', $sourceFile);
        $filenameArr = ($withEXT) ? end($splitURL) : (explode('.', end($splitURL)));
        return ($withEXT) ? $filenameArr : $filenameArr[0];
    }
    /**
     * Method to obtain videos data for a column
     *
     * @param int $videoID
     * @param array $columns
     * @return string
     */
    public function obtainVideoData($videoID, $columns)
    {
        return $this->video->select($columns)->where('id', $videoID)->first();
    }
    /**
     * Method to track the audio transcoding status
     *
     * @return void
     */
    public function trackTranscoding()
    {
        $jobStatus = '';
        $jobLists = $this->video->select('id', 'trailer_jobid')->where('trailer_status', '!=', 'Complete')->where('trailer_jobid', '!=', '')->get();
        foreach ($jobLists as $job) {
            try {
                $AWSTranscoderClient = ElasticTranscoderClient::factory($this->AWSConfigurations);
                $jobResult = $AWSTranscoderClient->readJob(array('Id' => $job['trailer_jobid']));
                if ($jobResult['Job']) {
                    $jobStatus = $jobResult['Job']['Status'];
                    $videoModel = $this->video->findorfail($job['id']);
                    $videoModel->trailer_status = $jobStatus;
                    $videoModel->save();
                }
            } catch (\Exception $exception) {
                app('log')->error(' ###File : ' . $exception->getFile() . ' ##Line : ' . $exception->getLine() . ' #Error : ' . $exception->getMessage());
            }
        }
    }
}
