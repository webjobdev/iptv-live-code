<?php

/**
 * Scheduler to convert live video recording to VOD videos
 *
 * @name ConvertLiveVideoToVodVideoScheduler
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2018 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */

namespace Contus\Video\Schedulers;

use Contus\Base\Schedulers\Scheduler;
use Contus\Video\Models\Video;
use Contus\Video\Models\LiveVideoRecordings;
use GuzzleHttp\Psr7;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\File as Makefile;
use Contus\Video\Repositories\AWSUploadRepository;
use Contus\Video\Models\TranscodedVideo;
use Contus\Video\Models\VideoPreset;

class ConvertLiveVideoToVodVideoScheduler extends Scheduler{
    /**
     * Class intializer
     *
     * @return void
     */
    public function __construct(){
        parent::__construct();
        $this->video = new Video();
        $this->live_video_recordings = new LiveVideoRecordings();
        $this->pipelineId = env('AWS_PIPELINE_ID');
        $this->awsRepository = new AWSUploadRepository(new TranscodedVideo(), new VideoPreset());
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
            $liveStreamVideoData = array();
            $streamIds = $this->video->select('id','stream_id','title','creator_id')
                        ->where('is_live',1)
                        ->where('liveStatus','recording')
                        ->where('live_recording_confirmation',1)
                        ->where('live_recorded_status',0)->get()->makeVisible(['id','creator_id'])->toArray();
            foreach($streamIds as $liveStream){
                $liveStreamVideoData['videoId'] = $liveStream['id'];
                $liveStreamVideoData['videoTitle'] = $liveStream['title'];
                $liveStreamVideoData['videoCreator_id'] = $liveStream['creator_id'];
                $liveStreamId = $liveStream['stream_id'];
                $endPoint = '/live_streams/' . $liveStreamId . '/state/';
                $dataParam = 'live_stream.state';
                $wowzaLiveStreamStatusResponse = $this->handleWowzaResponse('get',$endPoint, $dataParam, $liveStreamVideoData['videoId']);
                if($wowzaLiveStreamStatusResponse != null &&  $wowzaLiveStreamStatusResponse == "stopped"){
                    $endPoint = '/transcoders/'.$liveStreamId.'/recordings';
                    $dataParam = 'recordings';
                    $wowzaRecordingsData = $this->handleWowzaResponse('get',$endPoint, $dataParam, $liveStreamVideoData['videoId']);
                    $this->handleRecordings($wowzaRecordingsData,$liveStreamVideoData);
                }
            }
        };
    }
    /**
     * Method to handle the stream recordings
     * 
     * @param $data array
     * @param $liveStreamVideoData array
     * @return void
     */
    public function handleRecordings($data,$liveStreamVideoData){
        $data = end($data);
        if(!empty($data) && $data['state'] == 'completed'){
            $targetVideoURL =  $data['download_url'];
            $this->downloadRecordings($targetVideoURL,$liveStreamVideoData,$data);
        }else{

            if(!empty($data) && $data['state'] == 'failed'){
                $this->updateRecordingError($liveStreamVideoData['videoId']);
            }
            if(!empty($data) && $data['state'] == 'no_video') {
                $this->updateRecordingError($liveStreamVideoData['videoId']);
            }
            app('log')->error(" ###File : ConvertLiveVideoToVodVideoScheduler. Video job transcoding failed for state ".$data['state']." for the live stream video ".$liveStreamVideoData['videoId']);
        }
    }
    /**
     * Method to download recordings and upload the file to S3 bucket
     * 
     * @param $videoURL string
     * @param $liveStreamVideoData array
     * @param $recordedData array
     * @return void
     */
    public function downloadRecordings($videoURL,$liveStreamVideoData,$recordedData){
        $fileName = $localTmpPath = '';
        $fileName = $liveStreamVideoData['videoId'].'-'.$recordedData['id'].'-'.rand(100000, 999999).'.mp4';
        $localTmpPath = public_path().DIRECTORY_SEPARATOR.config("contus.base.media.live_video_recordings.temporary_storage_path");
        $targetVideoFilePath = $localTmpPath.DIRECTORY_SEPARATOR.$fileName;
        $videoContents = $this->getFileContents($videoURL);
        if(!empty($videoContents)){
            try{
                if (!file_exists($localTmpPath)) {
                    Makefile::makeDirectory($localTmpPath, 0777, true, true);
                }
                $moveFileToLocalPath = file_put_contents( $targetVideoFilePath, $videoContents);

                $getID3 = new \getID3();
                $fileGetProperties = $getID3->analyze($targetVideoFilePath);

                $video_height = (!empty($fileGetProperties['video']['resolution_y'])) ? $fileGetProperties['video']['resolution_y'] : 0;
                $video_duration = formatTime($fileGetProperties['playtime_string']);

                Video::where('id', $liveStreamVideoData['videoId'])->update(['video_duration' => $video_duration, 'video_height' => $video_height]);
                app('cache')->tags('videos')->flush();

                if($moveFileToLocalPath !== false){
                    $awsDestinationKey = config("contus.base.media.live_video_recordings.s3_location_video_source").$fileName;
                    $uploadedVideoURL = $this->awsRepository->uploadFileToS3($targetVideoFilePath, $awsDestinationKey);
                    if ($uploadedVideoURL) {
                        app('log')->info(" ###File : ConvertLiveVideoToVodVideoScheduler. Video for the recording ".$recordedData['id']." uploaded to s3 bucket for the live stream video ".$liveStreamVideoData['videoId']);
                        $videoFilename = str_replace('.mp4','',$fileName);

                        $result['file_slug']    = $videoFilename;
                        $result['id']           = $liveStreamVideoData['videoId'];
                        $result['creator_id']   = $liveStreamVideoData['videoCreator_id'];
                        $result['video_height']   = $video_height;
                        $jobId = $this->awsRepository->transcodeFile($this->pipelineId, $awsDestinationKey,
                            $result);
                        \Log::info($jobId);

                        if($jobId){
                            $this->updateLiveVideoDetails($uploadedVideoURL,$liveStreamVideoData['videoId'],$jobId);
                            $this->updateLiveRecordings($liveStreamVideoData['videoId'],$recordedData['id']);
                        }else{
                            $this->updateRecordingError($liveStreamVideoData['videoId']);
                            app('log')->error(" ###File : ConvertLiveVideoToVodVideoScheduler. Video job transcoding failed for the recording ".$recordedData['id']." for the live stream video ".$liveStreamVideoData['videoId']);
                        }
                    }else{
                        $this->updateRecordingError($liveStreamVideoData['videoId']);
                        app('log')->error(" ###File : ConvertLiveVideoToVodVideoScheduler. Video for the recording ".$recordedData['id']." failed to uploaded to s3 bucket for the live stream video ".$liveStreamVideoData['videoId']);
                    }
                }
            }catch (Exception $exception){
                $this->updateRecordingError($liveStreamVideoData['videoId']);
                app('log')->error(' ###File : ' . $exception->getFile() . ' ##Line : ' . $exception->getLine() . ' #Error : ' . $exception->getMessage());
            }
        }else{
            $this->updateRecordingError($liveStreamVideoData['videoId']);
            app('log')->error(" ###File : ConvertLiveVideoToVodVideoScheduler. Video contents is empty. Check whether 'allow_url_fopen' or 'Curl' extension enabled");
        }
    }
    /**
     * Method to update video status when recordings fails in any case
     * 
     * @param int $videoID
     * @return void
     */
    public function updateRecordingError($videoID){
        $video = $this->video->find($videoID);
        $video->job_status = 'Complete';
        $video->transcode_status = 'Complete';
        $video->recording_status = 'Error Recording';
        $video->liveStatus = 'stopped';
        $video->is_live = 1;
        $video->save();
    }
    /**
     * Method to update live video details after recordings
     * 
     * @param $uploadedVideoURL string
     * @param $videoID int
     * @return void
     */
    public function updateLiveVideoDetails($uploadedVideoURL,$videoID,$jobId){
        $video = $this->video->find($videoID);
        $video->video_url = $uploadedVideoURL;
        $video->is_live = 0;
        $video->pipeline_id = $this->pipelineId;
        $video->job_id = $jobId;
        $video->job_status = 'Progressing';
        $video->transcode_status = 'Progressing';
        $video->recording_status = '';
        $video->live_recorded_status = 1;
        $video->liveStatus = '';
        $video->username = '';
        $video->save();
    }
    /**
     * Method to update live video recording details after recordings
     * 
     * @param $videoID int
     * @param $recordingID string
     * @return void
     */
    public function updateLiveRecordings($videoID,$recordingID){
        $live_video_recordings = $this->live_video_recordings;
        $live_video_recordings->live_video_id = $videoID;
        $live_video_recordings->live_video_recording_id = $recordingID;
        $live_video_recordings->status = 1;
        $live_video_recordings->save();
    }
    /**
     * Method to check whether curl extension in enabled
     * 
     * @return boolean
     */
    public function _isCurl(){
        return function_exists('curl_version');
    }
    /**
     * Method to get video content
     * 
     * @param $videoURL string
     * @return video/mp4
     */
    public function getFileContents($videoURL){
        $contents = '';
        if( ini_get('allow_url_fopen') ){
            $contents = file_get_contents($videoURL);
        }else{
            if($this->_isCurl()){
                $ch = curl_init();
                $timeout = 5;
                curl_setopt($ch, CURLOPT_URL, $videoURL);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
                $data = curl_exec($ch);
                curl_close($ch);
                $contents = $data;
            }
        }
        return $contents;
    }
    /**
     * Method to handle the wowza response using Guzzle Client
     * 
     * @param $method string
     * @param $endPoint string
     * @param $dataParam string
     */
    public function handleWowzaResponse($method,$endPoint, $dataParam, $videoID){
        $client = new \GuzzleHttp\Client ();
        $response = '';
        $headers = [
                    'headers' => [ 
                        'wsc-api-key' => getenv ( 'WOWZA_CLOUD_API_KEY' ),
                        'wsc-access-key' => getenv ( 'WOWZA_CLOUD_ACCESS_KEY' ),
                        'Content-Type' => 'application/json' 
                    ]
                ];
        try {
            switch ($method){
                case 'get':
                    $response = $client->get ( (getenv ( 'WOWZA_API_PRODUCTION_URL' ) . $endPoint),$headers);
                    break;
                case 'put':
                    $response = $client->put ( (getenv ( 'WOWZA_API_PRODUCTION_URL' ) . $endPoint), $headers);
                    break;
                default:
                break;
            }
            return array_get ( json_decode ( $response->getBody (), 1 ), $dataParam);
        }catch ( RequestException $exception ) {
            $this->updateRecordingError($videoID);
            app('log')->error(' ###File : ' . $exception->getFile() . ' ##Line : ' . $exception->getLine() . ' #Error : ' . $exception->getMessage());
        }
    }
}
