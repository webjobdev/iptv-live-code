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
use Contus\Video\Models\LiveVideoRecordings;
use Contus\Video\Models\TranscodedVideo;
use Contus\Video\Models\Video;
use Contus\Video\Models\VideoPreset;
use Contus\Video\Repositories\AWSUploadRepository;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class ConvertLiveVideoToVodVideoScheduler extends Scheduler
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
        return function () {};
        // TO DO - Remove the above line and correct the below code to handel antmedia and wowza live streaming
        return function () {
            $liveStreamVideoData = array();
            $streamIds = $this->video->select('id', 'stream_id', 'title', 'creator_id', 'stream_id', 'video_url')
                ->where('is_live', 1)
                ->where('liveStatus', 'recording')
                ->where('live_recording_confirmation', 1)
                ->where('live_recorded_status', 0)->get()->makeVisible(['id', 'creator_id'])->toArray();

            app('log')->info('Live Video to VOD Scheduler');

            foreach ($streamIds as $liveStream) {
                app('log')->info('Live Video');
                $liveStreamVideoData['videoId'] = $liveStream['id'];
                $liveStreamVideoData['videoTitle'] = $liveStream['title'];
                $liveStreamVideoData['videoCreator_id'] = $liveStream['creator_id'];
                $liveStreamVideoData['stream_id'] = $liveStream['creator_id'];
                $liveStreamId = $liveStream['stream_id'];
                if (env('LIVE_TYPE') == 'wowza' && isset($liveStream['stream_id'])) {
                    $endPoint = '/live_streams/' . $liveStreamId . '/state/';
                    $dataParam = 'live_stream.state';
                    $wowzaLiveStreamStatusResponse = $this->handleWowzaResponse('get', $endPoint, $dataParam, $liveStreamVideoData['videoId']);
                    if ($wowzaLiveStreamStatusResponse != null && $wowzaLiveStreamStatusResponse == "stopped") {
                        $endPoint = '/transcoders/' . $liveStreamId . '/recordings';
                        $dataParam = 'recordings';
                        $wowzaRecordingsData = $this->handleWowzaResponse('get', $endPoint, $dataParam, $liveStreamVideoData['videoId']);
                        $endPointRecording = '/recordings/' . $wowzaRecordingsData[0]['id'];
                        $dataParam = 'recording';
                        $wowzaRecordingsDatas = $this->handleWowzaResponse('get', $endPointRecording, $dataParam, $liveStreamVideoData['videoId']);
                        $this->handleRecordings($wowzaRecordingsDatas, $liveStreamVideoData);
                    }
                }
                if (env('LIVE_TYPE') == 'antmedia' && isset($liveStream['stream_id'])) {
                    $data = [];
                    \Log::info($liveStream);
                    if (isset($liveStream['video_url'])) {
                        $data['download_url'] = $liveStream['video_url'];
                        $data['state'] = 'completed';
                        $data['id'] = $liveStream['id'];
                        $this->handleRecordings($data, $liveStreamVideoData);
                    }
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
    public function handleRecordings($data, $liveStreamVideoData)
    {
        if (!empty($data) && $data['state'] == 'completed') {
            $targetVideoURL = $data['download_url'];
            $this->downloadRecordings($targetVideoURL, $liveStreamVideoData, $data);
        } else {

            if (!empty($data) && $data['state'] == 'failed') {
                $this->updateRecordingError($liveStreamVideoData['videoId']);
            }
            if (!empty($data) && $data['state'] == 'no_video') {
                $this->updateRecordingError($liveStreamVideoData['videoId']);
            }
            app('log')->error(" ###File : ConvertLiveVideoToVodVideoScheduler. Video job transcoding failed for state " . $data['state'] . " for the live stream video " . $liveStreamVideoData['videoId']);
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
    public function downloadRecordings($videoURL, $liveStreamVideoData, $recordedData)
    {
        $fileName = $localTmpPath = '';
        $fileName = $liveStreamVideoData['videoId'] . '-' . $recordedData['id'] . '-' . rand(100000, 999999) . '.mp4';
        $videoContents = $this->getFileContents($videoURL, $fileName, $liveStreamVideoData['videoId']);
    }
    /**
     * Method to update video status when recordings fails in any case
     *
     * @param int $videoID
     * @return void
     */
    public function updateRecordingError($videoID)
    {
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
    public function updateLiveVideoDetails($uploadedVideoURL, $videoID, $jobId)
    {
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
    public function updateLiveRecordings($videoID, $recordingID)
    {
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
    public function _isCurl()
    {
        return function_exists('curl_version');
    }
    /**
     * Method to get video content
     *
     * @param $videoURL string
     * @return video/mp4
     */
    public function getFileContents($videoURL, $filename, $id)
    {
        $contents = '';
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => getenv('UPLOAD_NODE_API'),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            // CURLOPT_POSTFIELDS =>"{\n \"url\" : $videoURL,\n    \"filename\" : \"$filename\"\n}",
            CURLOPT_POSTFIELDS => "{\n \"url\" : \"$videoURL\",\n    \"filename\" : \"$filename\",\n    \"id\": $id\n}",
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        //  \Log::info($response->getBody());
        return $contents;
    }

    public function readfile_chunked($filename, $retbytes = true)
    {
        $file = '/path/to/files/photo.jpg';
        if (is_file($file)) {
            sendHeaders($file, 'image/jpeg', 'My picture.jpg');
            ob_clean();
            flush();
            @readfile($file);
            exit;
        }
    }

    /**
     * Method to handle the wowza response using Guzzle Client
     *
     * @param $method string
     * @param $endPoint string
     * @param $dataParam string
     */
    public function handleWowzaResponse($method, $endPoint, $dataParam, $videoID)
    {
        $client = new \GuzzleHttp\Client();
        $response = '';
        $headers = [
            'headers' => [
                'wsc-api-key' => getenv('WOWZA_CLOUD_API_KEY'),
                'wsc-access-key' => getenv('WOWZA_CLOUD_ACCESS_KEY'),
                'Content-Type' => 'application/json',
            ],
        ];
        try {
            switch ($method) {
                case 'get':
                    $response = $client->get((getenv('WOWZA_API_PRODUCTION_URL') . $endPoint), $headers);
                    break;
                case 'put':
                    $response = $client->put((getenv('WOWZA_API_PRODUCTION_URL') . $endPoint), $headers);
                    break;
                default:
                    break;
            }
            return array_get(json_decode($response->getBody(), 1), $dataParam);
        } catch (RequestException $exception) {
            $this->updateRecordingError($videoID);
            app('log')->error(' ###File : ' . $exception->getFile() . ' ##Line : ' . $exception->getLine() . ' #Error : ' . $exception->getMessage());
        }
    }
}
