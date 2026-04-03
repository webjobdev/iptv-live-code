<?php

/**
 * Live Sync Scheduler
 *
 * @name LiveSyncScheduler
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
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

class LiveStreamStatusScheduler extends Scheduler
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
        $this->videoS3SourceFolderName = config("contus.base.media.live_video_recordings.s3_location_video_source");
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
           // \Log::info("LIVE STATUS SCHEDULER");
            $streams = Video::where('is_live', 1)
                ->where('liveStatus', 'streaming')->get();
            foreach ($streams as $vod) {
                $data = $this->handleAntMediaResponse($vod);
                if (isset($data->streamId)) {
                    \Log::info($data->streamId);
                    if ($data->streamId != null && $data->status == 'finished') {
                        Video::where('stream_id', $data->streamId)->update([
                            'liveStatus' => 'Complete',
                            'is_active' => 0,
                        ]);
                    }
                }
            }
        };
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

    public function handleAntMediaResponse($data)
    {
        $client = new \GuzzleHttp\Client();
        $response = '';
        $URL = getenv('ANTMEDIA_API_PRODUCTION_URL') . $data->stream_id;
        $headers = [
            'Accept' => 'application/json, text/plain, */*',
            'Host' => env('WEBRTC_HOST', 'webrtc.vplayed.com'),
            'Referer' => 'https://' . env('WEBRTC_HOST', 'webrtc.vplayed.com') . '/',
            'Content-Type' => 'application/json',
        ];
        $method = 'get';
        $dataParam = '';
        try {
            switch ($method) {
                case 'get':
                    $response = $client->get(($URL), ['headers' => $headers]);
                    break;
                default:
                    break;
            }
            if (!empty($method) && $method == 'post') {
                $result = json_decode($response->getBody(), 1);
            } else {
                $result = array_get(json_decode($response->getBody(), 1), $dataParam);
            }
            return $result;
        } catch (RequestException $exception) {
            $video = Video::where('id', $data->id)->first();
            if ($video != null) {
                \Log::info("LOG 2");
                $video->liveStatus = 'started';
                $video->is_active = 1;
                $video->save();
            }
            app('log')->error(' ###File : ' . $exception->getFile() . ' ##Line : ' . $exception->getLine() . ' #Error : ' . $exception->getMessage());
        }
    }

}
