<?php

/**
 * Collection Repository
 *
 * To manage the functionalities related to the Collection module from Collection Controller
 *
 * @name CommentsRepository
 * @vendor Contus
 * @package Collection
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */

namespace Contus\Video\Repositories;

use Auth;
use Carbon\Carbon;
use Contus\Base\Helpers\StringLiterals;
use Contus\Base\Repository as BaseRepository;
use Contus\Video\Models\Category;
use Contus\Video\Models\CollectionVideo;
use Contus\Video\Models\Group;
use Contus\Video\Models\LiveEventOrganization;
use Contus\Video\Models\LiveVideoRecordings;
use Contus\Video\Models\Tag;
use Contus\Video\Models\TranscodedVideo;
use Contus\Video\Models\Video;
use Contus\Video\Models\VideoAds;
use Contus\Video\Models\VideoCategory;
use Contus\Video\Models\VideoPreset;
use Contus\Video\Models\VideoSeason;
use Contus\Video\Repositories\AWSUploadRepository;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class LiveStreamRepository extends BaseRepository
{
    /**
     * Class initializer
     *
     * @return void
     */
    public function __construct(AWSUploadRepository $awsRepository)
    {
        parent::__construct();
        $this->liveStream = new Video();
        $this->tag = new Tag();
        $this->collectionVideo = new CollectionVideo();
        $this->live_video_recordings = new LiveVideoRecordings();
        $this->awsRepository = new AWSUploadRepository(new TranscodedVideo(), new VideoPreset());
    }

    /**
     * This method is use to trigger wowza live stream create api.
     *
     * @see \\Contus\Base\Contracts\ResourceInterface::store()
     *
     * @return boolean
     */
    public function store()
    {
        return $this->createLiveStream($this->request->all());
    }

    public function liveradio()
    {
        return $this->creareradiostream($this->request->all());
    }

    public function liveevent()
    {
        return $this->createevntstream($this->request->all());
    }
    /**
     * Method to handle the wowza response using Guzzle Client
     *
     * @param $method string
     * @param $endPoint string
     * @param $dataParam string
     */
    public function handleWowzaResponse($method, $endPoint, $postData = array(), $dataParam = '')
    {
        $client = new \GuzzleHttp\Client();
        $response = '';
        $URL = getenv('WOWZA_API_PRODUCTION_URL') . $endPoint;
        $headers = [
            'wsc-api-key' => getenv('WOWZA_CLOUD_API_KEY'),
            'wsc-access-key' => getenv('WOWZA_CLOUD_ACCESS_KEY'),
            'Content-Type' => 'application/json',
        ];
        try {
            switch ($method) {
                case 'get':
                    $response = $client->get(($URL), ['headers' => $headers]);
                    break;
                case 'put':
                    $response = $client->put(($URL), ['headers' => $headers]);
                    break;
                case 'delete':
                    $response = $client->delete(($URL), ['headers' => $headers]);
                    break;
                case 'post':
                    $response = $client->post(($URL), ['headers' => $headers, 'body' => json_encode($postData)]);
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
            app('log')->error(' ###File : ' . $exception->getFile() . ' ##Line : ' . $exception->getLine() . ' #Error : ' . $exception->getMessage());
        }
    }
    /**
     * Method to update live stream status as start or stop
     *
     * @param $liveStatus string
     * @param $liveStreamId integer
     * @return void
     */
    public function updateLiveStreamStatus($liveStatus, $liveStreamid)
    {
        if ($liveStatus == 'stopped') {
            $this->liveStream->where('id', $liveStreamid)->update([
                'liveStatus' => 'Complete',
                'scheduledStartTime' => date("Y-m-d H:i:s"),
                'job_status' => 'Complete',
                'transcode_status' => 'Complete',
                'is_active' => 0,
            ]);
        } else {
            $this->liveStream->where('id', $liveStreamid)->update([
                'liveStatus' => $liveStatus,
                'scheduledStartTime' => date("Y-m-d H:i:s"),
                'job_status' => 'Complete',
                'transcode_status' => 'Complete',
                'is_active' => 1,
            ]);
        }
    }
    /**
     * Method to handle exception for both start and stop of live stream
     * @param array $e
     */
    public function handleLivestreamException($e)
    {
        if (array_get(json_decode($e->getResponse()->getBody(), 1), 'meta.message') == "The requested resource has been deleted.") {
            $this->liveStream->where('id', $this->request->id)->delete();
            return 'deleted';
        }
        return array_get(json_decode($e->getResponse()->getBody(), 1), 'meta.message');
    }
    /**
     * /**
     * This method is used to create live stream and save the details in db
     *
     * @param array $requestData
     * @return boolean
     */
    public function createLiveStream($requestData)
    {

        if (!empty($requestData['liveType']) && $requestData['liveType'] == 'hls') {
            $this->setRules([
                'hls' => 'required|url',
                'category' => 'required',
                'title' => 'required'
            ]);
            $this->_validate();
            $liveStream = $this->liveStream;
            $liveStream->stream_id = 'wowza';
            $liveStream->encoder_type = 'wowza';
            $liveStream->source_url = 'wowza';
            $liveStream->stream_name = 'wowza';
            $liveStream->username = config()->get('wowza.wowza.username');
            $liveStream->password = config()->get('wowza.wowza.password');
            $liveStream->liveStatus = 'ready';
            $liveStream->job_status = 'Complete';
            $liveStream->transcode_status = 'Complete';
            $liveStream->is_live = '1';
            $liveStream->is_hls = '1';
            $liveStream->title = $requestData['title'];

            if (isset($requestData['thumbnail_image']) && $requestData['thumbnail_image'] != '') {
                $thumbUrl = explode("/", $requestData['thumbnail_image']);
                $fileName = $liveStream->getImageBaseName($thumbUrl[count($thumbUrl) - 1]);
                // $folderName = config("contus.base.image.thumbnail.s3_location");
                $localStoragePath = config("app.url") . config("contus.base.image.thumbnail.temporary_image_storage_path");
                $localImagePath = $localStoragePath . DIRECTORY_SEPARATOR . $fileName;
                // $s3BucketImgFilename = $this->uploadTos3Bucket($fileName, $folderName, $localStoragePath);
                // $s3BucketImgURL = $folderName . $s3BucketImgFilename;
                $liveStream->thumbnail_image = $localImagePath;
            }
            if (isset($requestData['poster_image']) && $requestData['poster_image'] != '') {
                $thumbUrl = explode("/", $requestData['poster_image']);
                $fileName = $liveStream->getImageBaseName($thumbUrl[count($thumbUrl) - 1]);
                // $folderName = config("contus.base.image.posters.s3_location");
                $localStoragePath = config("app.url") . config("contus.base.image.posters.temporary_image_storage_path");
                // $s3BucketImgFilename = $this->uploadTos3Bucket($fileName, $folderName, $localStoragePath);
                // $s3BucketImgURL = $folderName . $s3BucketImgFilename;
                $localImagePath = $localStoragePath . DIRECTORY_SEPARATOR . $fileName;
                $liveStream->poster_image = $localImagePath;

                // $fileName = $adminUser->getImageBaseName($this->request->profile_image);
                // $localStoragePath = config("app.url")  .  config("contus.base.image.admin_user_profile_image.temporary_image_storage_path");
                // $localImagePath = $localStoragePath . DIRECTORY_SEPARATOR . $fileName;
                // $adminUser->profile_image = $localImagePath;
            }

            // NOTE : To format the scheduled time into db format
            $date = (!empty($requestData['scheduled_time'])) ? $requestData['scheduled_time'] : '';
            $liveStream->scheduledStartTime = $date; // format_schedule_date($date);

            $liveStream->description = $this->request->description ? $this->request->description : '';
            $liveStream->hls_playlist_url = isset($requestData['hls']) ? $requestData['hls'] : "";

            $liveStream->broadcast_location = 'wowza';
            $liveStream->creator_id = 1;
            $liveStream->updator_id = 1;

            $liveStream->is_active = $requestData['is_active'] ? 1 : 0;
            $liveStream->is_notify = $requestData['is_notify'] ? 1 : 0;
            $liveStream->is_premium = $requestData['is_premium'] ? 1 : 0;
            $liveStream->save();
            $this->saveVideoCategories($liveStream);

            return 'success';
        } else {
            $this->setRules(['aspect_ratio' => 'required', 'category' => 'required', 'title' => 'required']);
            $this->_validate();
        }
        $aspectRatio = $requestData['aspect_ratio'];
        $aspectRatio = explode("X", $aspectRatio);
        $aspect_ratio_width = $aspectRatio[0];
        $aspect_ratio_height = $aspectRatio[1];

        if (env('LIVE_TYPE') == 'wowza') {
            /**
             * /**
             * This method is used for encoder is wowza_gocoder
             *
             * @return array
             */
            //event_name
            $requestData['encoder_type'] = 'push';
            $requestData['encoder'] = 'other_rtmp';
            $requestData['broadcast_location'] = env('WOWZA_BROADCAST_LOCATION', 'asia_pacific_singapore');
            if ($requestData['encoder_type'] == 'push') {
                $body = [
                    'live_stream' => [
                        'name' => $requestData['title'],
                        'transcoder_type' => 'transcoded',
                        'billing_mode' => 'pay_as_you_go',
                        'broadcast_location' => $requestData['broadcast_location'],
                        'encoder' => $requestData['encoder'],
                        "use_stream_source" => 'false',
                        'delivery_method' => $requestData['encoder_type'],
                        'aspect_ratio_width' => $aspect_ratio_width,
                        'aspect_ratio_height' => $aspect_ratio_height,
                        'player_type' => 'wowza_player',
                        'player_responsive' => 'true',
                        'recording' => 'true',
                        'video_fallback' => 'true',
                        'low_latency' => true,
                        "buffer_size" => 0,
                    ],
                ];
            }

            $client = new \GuzzleHttp\Client();
            try {
                $wowzaResponse = $this->handleWowzaResponse('post', '/live_streams', $body);
                $liveStream = $this->liveStream;
                $liveStream->stream_id = $wowzaResponse['live_stream']['id'];
                $liveStream->encoder_type = $requestData['encoder_type'];
                $liveStream->source_url = isset($wowzaResponse['live_stream']['source_connection_information']['primary_server']) ? $wowzaResponse['live_stream']['source_connection_information']['primary_server'] : "";
                $liveStream->stream_name = isset($wowzaResponse['live_stream']['source_connection_information']['stream_name']) ? $wowzaResponse['live_stream']['source_connection_information']['stream_name'] : "";
                $liveStream->username = isset($wowzaResponse['live_stream']['source_connection_information']['username']) ? $wowzaResponse['live_stream']['source_connection_information']['username'] : "";
                $liveStream->password = isset($wowzaResponse['live_stream']['source_connection_information']['password']) ? $wowzaResponse['live_stream']['source_connection_information']['password'] : "";
                $liveStream->liveStatus = 'ready';
                $liveStream->job_status = 'Complete';
                $liveStream->transcode_status = 'Complete';
                $liveStream->is_live = '1';
                $liveStream->live_recorded_status = 0;
                $liveStream->title = $requestData['title'];

                // NOTE : To format the scheduled time into db format
                $date = (!empty($requestData['scheduled_time'])) ? $requestData['scheduled_time'] : '';
                $liveStream->scheduledStartTime = $date; //format_schedule_date($date);

                $liveStream->description = $this->request->description ? $this->request->description : '';
                $liveStream->hls_playlist_url = isset($wowzaResponse['live_stream']['player_hls_playback_url']) ? $wowzaResponse['live_stream']['player_hls_playback_url'] : "";
                $liveStream->broadcast_location = isset($wowzaResponse['live_stream']['broadcast_location']) ? $wowzaResponse['live_stream']['broadcast_location'] : "";
                $liveStream->presenter = isset($requestData['presenter']) ? $requestData['presenter'] : "";
                $liveStream->creator_id = 1;
                $liveStream->updator_id = 1;

                if (isset($requestData['thumbnail_image']) && $requestData['thumbnail_image'] != '') {
                    $thumbUrl = explode("/", $requestData['thumbnail_image']);

                    $fileName = $liveStream->getImageBaseName($thumbUrl[count($thumbUrl) - 1]);
                    $folderName = config("contus.base.image.thumbnail.s3_location");
                    $localStoragePath = public_path() . DIRECTORY_SEPARATOR . config("contus.base.image.thumbnail.temporary_image_storage_path");
                    $s3BucketImgFilename = $this->uploadTos3Bucket($fileName, $folderName, $localStoragePath);
                    $s3BucketImgURL = $folderName . $s3BucketImgFilename;
                    $liveStream->thumbnail_image = $s3BucketImgURL;
                }
                if (isset($requestData['poster_image']) && $requestData['poster_image'] != '') {
                    $thumbUrl = explode("/", $requestData['poster_image']);
                    $fileName = $liveStream->getImageBaseName($thumbUrl[count($thumbUrl) - 1]);
                    $folderName = config("contus.base.image.posters.s3_location");
                    $localStoragePath = public_path() . DIRECTORY_SEPARATOR . config("contus.base.image.posters.temporary_image_storage_path");
                    $s3BucketImgFilename = $this->uploadTos3Bucket($fileName, $folderName, $localStoragePath);
                    $s3BucketImgURL = $folderName . $s3BucketImgFilename;
                    $liveStream->poster_image = $s3BucketImgURL;
                }
                // if (isset($requestData[StringLiterals::POSTERIMAGE])) {
                //     $posterUrl = explode("/", $requestData['poster_image']);
                //     $liveStream->poster_image = $posterUrl [count($posterUrl) - 1];
                // }
                $liveStream->is_active = 1;
                $liveStream->is_notify = $requestData['is_notify'] ? 1 : 0;
                $liveStream->is_premium = $requestData['is_premium'] ? 1 : 0;
                $liveStream->aspect_ratio = (!empty($requestData['aspect_ratio'])) ? $requestData['aspect_ratio'] : '';
                $liveStream->save();

                $this->saveVideoCategories($liveStream);
                return 'success';
            } catch (RequestException $e) {
                \Log::info($e->getMessage() . $e->getLine());
                return $e->getMessage() . $e->getLine();
            }
        }

        if (env('LIVE_TYPE') == 'antmedia') {

            $scheduleDate = (!empty($requestData['scheduled_time'])) ? $requestData['scheduled_time'] : '';
            $now = Carbon::now();
            $unique_code = $now->format('YmdHisu');
            $body = array(
                'streamId' => $unique_code,
                'status' => '',
                'type' => 'liveStream',
                'name' => $requestData['title'],
                'description' => '',
                'publish' => true,
                'date' => '',
                'plannedStartDate' => '',
                'duration' => 0,
                'endPointList' => array(
                    0 => array(
                        'type' => '',
                        'broadcastId' => '',
                        'streamId' => '',
                        'rtmpUrl' => '',
                        'name' => '',
                        'endpointServiceId' => '',
                        'serverStreamId' => '',
                    ),
                ),
                'publicStream' => true,
                'is360' => true,
                'listenerHookURL' => env('APP_URL') . 'api/admin/live/notify',
                'category' => '',
                'ipAddr' => '',
                'username' => '',
                'password' => '',
                'quality' => '',
                'speed' => 0,
                'streamUrl' => '',
                'originAdress' => env('WEBRTC_HOST', 'webrtc.vplayed.com'),
                'mp4Enabled' => 0,
                'expireDurationMS' => 0,
                'rtmpURL' => '',
                'zombi' => true,
                'pendingPacketSize' => 0,
                'hlsViewerCount' => 0,
                'webRTCViewerCount' => 0,
                'rtmpViewerCount' => 0,
                'startTime' => 0,
                'receivedBytes' => 0,
                'bitrate' => 0,
                'userAgent' => '',
                'latitude' => '',
                'longitude' => '',
                'altitude' => '',
            );
            try {
                $antmediaResponse = $this->handleAntMediaResponse('post', 'create', $body);

                $dataurl = isset($antmediaResponse['rtmpURL']) ? $antmediaResponse['rtmpURL'] : "";
                \Log::info($dataurl);
                $url = str_replace(env('WEBRTC_IP'), "webrtc.vplayed.com", $dataurl);

                $liveStream = $this->liveStream;
                $liveStream->stream_id = $antmediaResponse['streamId'];
                $liveStream->encoder_type = isset($antmediaResponse['encoder_type']) ? $antmediaResponse['encoder_type'] : "";
                $liveStream->source_url = $url;
                $liveStream->stream_name = isset($antmediaResponse['name']) ? $antmediaResponse['name'] : "";
                $liveStream->liveStatus = 'ready';
                $liveStream->job_status = 'Complete';
                $liveStream->transcode_status = 'Complete';
                $liveStream->is_live = '1';
                $liveStream->live_recorded_status = 0;
                $liveStream->title = $requestData['title'];
                // NOTE : To format the scheduled time into db format
                $date = (!empty($requestData['scheduled_time'])) ? $requestData['scheduled_time'] : '';
                $liveStream->scheduledStartTime = $date; //format_schedule_date($date);

                $liveStream->description = $this->request->description ? $this->request->description : '';
                $liveStream->presenter = isset($requestData['presenter']) ? $requestData['presenter'] : "";
                $liveStream->creator_id = 1;
                $liveStream->updator_id = 1;
                if (env('LIVE_TYPE') == 'antmedia') {
                    $videoURL = 'https://' . env('WEBRTC_HOST', 'webrtc.vplayed.com') . ':5443/' . env('ANTMEDIA_VOD_TYPE') . '/streams/' . $antmediaResponse['streamId'] . '/' . $antmediaResponse['streamId'] . '.mp4';
                    $liveStream->video_url = $videoURL;
                }
                if (isset($requestData['thumbnail_image']) && $requestData['thumbnail_image'] != '') {
                    $thumbUrl = explode("/", $requestData['thumbnail_image']);

                    $fileName = $liveStream->getImageBaseName($thumbUrl[count($thumbUrl) - 1]);
                    $folderName = config("contus.base.image.thumbnail.s3_location");
                    $localStoragePath = public_path() . DIRECTORY_SEPARATOR . config("contus.base.image.thumbnail.temporary_image_storage_path");
                    $s3BucketImgFilename = $this->uploadTos3Bucket($fileName, $folderName, $localStoragePath);
                    $s3BucketImgURL = $folderName . $s3BucketImgFilename;
                    $liveStream->thumbnail_image = $s3BucketImgURL;
                }
                if (isset($requestData['poster_image']) && $requestData['poster_image'] != '') {
                    $thumbUrl = explode("/", $requestData['poster_image']);
                    $fileName = $liveStream->getImageBaseName($thumbUrl[count($thumbUrl) - 1]);
                    $folderName = config("contus.base.image.posters.s3_location");
                    $localStoragePath = public_path() . DIRECTORY_SEPARATOR . config("contus.base.image.posters.temporary_image_storage_path");
                    $s3BucketImgFilename = $this->uploadTos3Bucket($fileName, $folderName, $localStoragePath);
                    $s3BucketImgURL = $folderName . $s3BucketImgFilename;
                    $liveStream->poster_image = $s3BucketImgURL;
                }
                $liveStream->is_active = $requestData['is_active'] ? 1 : 0;
                $liveStream->is_notify = $requestData['is_notify'] ? 1 : 0;
                $liveStream->is_premium = $requestData['is_premium'] ? 1 : 0;
                $liveStream->aspect_ratio = (!empty($requestData['aspect_ratio'])) ? $requestData['aspect_ratio'] : '';
                $liveStream->save();
                $this->saveVideoCategories($liveStream);
                return 'success';
            } catch (RequestException $e) {
                \Log::info($e->getMessage() . $e->getLine());
                return $e->getMessage() . $e->getLine();
            }
        }
    }

    public function creareradiostream($requestData)
    {
        $this->setRules([
            'hls' => 'required|url',
            'title' => 'required',
            // 'description' => 'required',
            'category' => 'required'
        ]);
        $this->_validate();
        $liveStream = $this->liveStream;
        $liveStream->stream_id = '';
        $liveStream->encoder_type = '';
        $liveStream->source_url = '';
        $liveStream->stream_name = '';
        $liveStream->username = '';
        $liveStream->password = '';
        $liveStream->liveStatus = '';
        $liveStream->job_status = 'Complete';
        $liveStream->transcode_status = 'Complete';
        $liveStream->is_live = '2';
        $liveStream->is_hls = '1';
        $liveStream->title = $requestData['title'];

        if (isset($requestData['thumbnail_image']) && $requestData['thumbnail_image'] != '') {
            $thumbUrl = explode("/", $requestData['thumbnail_image']);
            $fileName = $liveStream->getImageBaseName($thumbUrl[count($thumbUrl) - 1]);
            // $folderName = config("contus.base.image.thumbnail.s3_location");
            $localStoragePath = config("app.url") . config("contus.base.image.thumbnail.temporary_image_storage_path");
            // $s3BucketImgFilename = $this->uploadTos3Bucket($fileName, $folderName, $localStoragePath);
            // $s3BucketImgURL = $folderName . $s3BucketImgFilename;
            $localIamgePath = $localStoragePath . DIRECTORY_SEPARATOR . $fileName;
            $liveStream->thumbnail_image = $localIamgePath;
        }
        if (isset($requestData['poster_image']) && $requestData['poster_image'] != '') {
            $thumbUrl = explode("/", $requestData['poster_image']);
            $fileName = $liveStream->getImageBaseName($thumbUrl[count($thumbUrl) - 1]);
            // $folderName = config("contus.base.image.posters.s3_location");
            $localStoragePath = config("app.url") . config("contus.base.image.posters.temporary_image_storage_path");
            // $s3BucketImgFilename = $this->uploadTos3Bucket($fileName, $folderName, $localStoragePath);
            // $s3BucketImgURL = $folderName . $s3BucketImgFilename;
            $localIamgePath = $localStoragePath . DIRECTORY_SEPARATOR . $fileName;
            $liveStream->poster_image = $localIamgePath;
        }

        // NOTE : To format the scheduled time into db format
        $date = (!empty($requestData['scheduled_time'])) ? $requestData['scheduled_time'] : '';
        $liveStream->scheduledStartTime = $date; // format_schedule_date($date);

        $liveStream->description = $this->request->description ? $this->request->description : '';
        $liveStream->hls_playlist_url = isset($requestData['hls']) ? $requestData['hls'] : "";

        $liveStream->broadcast_location = '';
        $liveStream->creator_id = 1;
        $liveStream->updator_id = 1;

        $liveStream->is_active = $requestData['is_active'] ? 1 : 0;
        $liveStream->is_notify = $requestData['is_notify'] ? 1 : 0;
        $liveStream->is_premium = $requestData['is_premium'] ? 1 : 0;
        $liveStream->save();
        // $this->saveVideoCategories($liveStream);
        $this->saveRadioCategories($liveStream);

        return 'success';
    }

    public function createevntstream($requestData)
    {
        $this->setRules([
            'hls' => 'required|url',
            'title' => 'required',
            // 'description' => 'required',
            // 'category' => 'required',
            'scheduled_time' => 'required',
            'expire_scheduled_time' => 'required',
            'organization' => 'required',
            'playback_token' => 'required',
            'policy' => 'required',
            // 'content_sets' => 'required',
            'streaming_provider' => 'required',
            'recordingStartTime' => 'required',
            'recordingEndTime' => 'required',
            'publish_date' => 'required',
            'drm_type' => 'required',
            'drm_profile' => 'required',
            'available_until' => 'required',
            'days' => 'required',
            'age_limit' => 'required'
        ]);
        $this->_validate();
        $liveStream = $this->liveStream;

        // Log::info('Initialized live stream model.');

        $liveStream->stream_id = '';
        $liveStream->encoder_type = '';
        $liveStream->source_url = '';
        $liveStream->stream_name = '';
        $liveStream->username = '';
        $liveStream->password = '';
        $liveStream->liveStatus = '';
        $liveStream->job_status = 'Complete';
        $liveStream->transcode_status = 'Complete';
        $liveStream->is_live = '3';
        $liveStream->is_hls = '1';
        $liveStream->title = $requestData['title'];

        if (isset($requestData['thumbnail_image']) && $requestData['thumbnail_image'] != '') {
            $thumbUrl = explode("/", $requestData['thumbnail_image']);
            $fileName = $liveStream->getImageBaseName($thumbUrl[count($thumbUrl) - 1]);
            // $folderName = config("contus.base.image.thumbnail.s3_location");
            $localStoragePath = config("app.url") . config("contus.base.image.thumbnail.temporary_image_storage_path");
            // $s3BucketImgFilename = $this->uploadTos3Bucket($fileName, $folderName, $localStoragePath);
            // $s3BucketImgURL = $folderName . $s3BucketImgFilename;
            $localImagePath = $localStoragePath . DIRECTORY_SEPARATOR . $fileName;
            $liveStream->thumbnail_image = $localImagePath;
            // Log::info('Processed thumbnail image.', ['thumbnail_path' => $localImagePath]);
        }
        if (isset($requestData['poster_image']) && $requestData['poster_image'] != '') {
            $thumbUrl = explode("/", $requestData['poster_image']);
            $fileName = $liveStream->getImageBaseName($thumbUrl[count($thumbUrl) - 1]);
            // $folderName = config("contus.base.image.posters.s3_location");
            $localStoragePath = config("app.url") . config("contus.base.image.posters.temporary_image_storage_path");
            // $s3BucketImgFilename = $this->uploadTos3Bucket($fileName, $folderName, $localStoragePath);
            // $s3BucketImgURL = $folderName . $s3BucketImgFilename;
            $localImagePath = $localStoragePath . DIRECTORY_SEPARATOR . $fileName;
            $liveStream->poster_image = $localImagePath;
            // Log::info('Processed poster image.', ['poster_path' => $localImagePath]);
        }

        // NOTE : To format the scheduled time into db format
        $date = (!empty($requestData['scheduled_time'])) ? $requestData['scheduled_time'] : '';
        $expire_scheduled_time = (!empty($requestData['expire_scheduled_time'])) ? $requestData['expire_scheduled_time'] : '';

        $recordingdate = (!empty($requestData['recordingStartTime'])) ? $requestData['recordingStartTime'] : '';
        $recordingendDate = (!empty($requestData['recordingEndTime'])) ? $requestData['recordingEndTime'] : '';
        $AvailableUntil = (!empty($requestData['available_until'])) ? $requestData['available_until'] : '';
        $publishDate = (!empty($requestData['publish_date'])) ? $requestData['publish_date'] : '';

        $liveStream->scheduledStartTime = $date; // format_schedule_date($date);
        $liveStream->scheduledEndTime = $expire_scheduled_time;

        $liveStream->recordingStartTime = $recordingdate;
        $liveStream->recordingEndTime = $recordingendDate;
        $liveStream->available_until = $AvailableUntil;
        $liveStream->publish_date = $publishDate;

        // $liveStream->organization = $requestData['organization'] ?? null;
        $liveStream->playback_token = $requestData['playback_token'];
        $liveStream->policy = $requestData['policy'];
        $liveStream->days = $requestData['days'];
        $liveStream->streaming_provider = $requestData['streaming_provider'];
        $liveStream->live_streaming_provider = $requestData['live_streaming_provider'];
        $liveStream->drm_type = $requestData['drm_type'] ?? null;
        $liveStream->drm_profile = $requestData['drm_profile'];
        $liveStream->age_limit = $requestData['age_limit'];
        $liveStream->scheduled_publishing = $requestData['scheduled_publishing'] ? 1 : 0;
        $liveStream->age_rating = $requestData['age_rating'] ? 1 : 0;
        $liveStream->catch_up_status = $requestData['catch_up_status'] ? 1 : 0;
        $liveStream->live_rewind_status = $requestData['live_rewind_status'] ? 1 : 0;

        $liveStream->description = $this->request->description ? $this->request->description : '';
        $liveStream->hls_playlist_url = isset($requestData['hls']) ? $requestData['hls'] : "";

        $liveStream->broadcast_location = '';
        $liveStream->creator_id = 1;
        $liveStream->updator_id = 1;

        $liveStream->is_active = $requestData['is_active'] ? 1 : 0;
        $liveStream->is_notify = $requestData['is_notify'] ? 1 : 0;
        $liveStream->is_premium = $requestData['is_premium'] ? 1 : 0;
        $liveStream->save();

        $user = Auth::user();

        foreach ($requestData['organization'] as $orgId) {
            LiveEventOrganization::updateOrCreate([
                'live_event_id' => $liveStream->id,
                'organization_id' => $orgId
            ], [
                'created_by' => $user->id
            ]);
        }

        return 'success';
    }

    /**
     * Method to start the livestream
     * @return boolean
     */
    public function startLiveStreamRepository()
    {
        $response = '';
        $liveStreamId = $this->liveStream->where('id', $this->request->id)->get()->first();

        if (getenv('LIVE_TYPE') == 'wowza') {
            if (!empty($liveStreamId) && $liveStreamId->is_hls == 0) {
                try {
                    $this->setRule('id', 'required')->_validate();
                    $endPoint = '/live_streams/' . $liveStreamId->stream_id . '/start/';
                    $response = $this->handleWowzaResponse('put', $endPoint, '', 'live_stream.state');
                    $this->updateLiveStreamStatus($response, $this->request->id);
                } catch (RequestException $e) {
                    $response = $this->handleLivestreamException($e);
                }
            } else {
                $this->updateLiveStreamStatus('started', $this->request->id);
                $response = 'Live video started';
            }
        }
        if (getenv('LIVE_TYPE') == 'antmedia') {
            $this->updateLiveStreamStatus('streaming', $this->request->id);
            $response = 'Live video streaming';
        }
        return $response;
    }
    /**
     * Method to stop the livestream
     *
     * @return boolean
     */
    public function stopLiveStreamRepository()
    {
        $response = '';
        $liveStreamStopId = $this->liveStream->where('id', $this->request->id)->get()->first();
        if (getenv('LIVE_TYPE') == 'wowza') {
            if (!empty($liveStreamStopId) && $liveStreamStopId->is_hls == 0) {
                try {
                    $this->setRule('id', 'required')->_validate();
                    $endPoint = '/live_streams/' . $liveStreamStopId->stream_id . '/stop/';
                    $response = $this->handleWowzaResponse('put', $endPoint, '', 'live_stream.state');
                    $response = ($this->request->record_status == 1) ? 'recording' : $response;
                    $this->updateLiveStreamStatus($response, $this->request->id);
                    $this->updateLivestreamRecordingsStatus($this->request->record_status, $this->request->id, $liveStreamStopId->stream_id);
                } catch (RequestException $e) {
                    $response = $this->handleLivestreamException($e);
                }
            } else {
                $status = ($this->request->record_status == 1) ? 'recording' : 'Complete';
                $this->updateLiveStreamStatus($status, $this->request->id);
                $response = 'Live video stopped';
            }
        }
        if (getenv('LIVE_TYPE') == 'antmedia') {
            if (!empty($liveStreamStopId) && $liveStreamStopId->is_hls == 0) {
                try {
                    //     $this->setRule ( 'id', 'required' )->_validate ();
                    $endPoint = $liveStreamStopId->stream_id . '/stop';
                    $response = $this->handleAntMediaResponse('delete', $endPoint, '', 'live_stream.state');
                    //$response  = $this->handleAntMediaResponse('delete', $liveStreamStopId->stream_id,'','live_stream.state');
                    $response = ($this->request->record_status == 1) ? 'recording' : 'Complete';
                    $this->updateLiveStreamStatus('stopped', $this->request->id);
                    $this->updateLivestreamRecordingsStatus($this->request->record_status, $this->request->id, $liveStreamStopId->stream_id);
                } catch (RequestException $e) {
                    $response = $this->handleLivestreamException($e);
                }
            } else {
                $this->updateLiveStreamStatus('stopped', $this->request->id);
                $response = 'Live video stopped';
            }
        }

        return $response;
    }
    /**
     * Method to delete livestream
     *
     * @param $videoIds array
     */
    public function deleteLivestream($videoIds)
    {
        foreach ($videoIds as $id) {
            $video = $this->liveStream->select('is_hls', 'stream_id')->where('id', $id)->get()->first();

            if ($video->is_hls == 0 && !empty($video->stream_id) && getenv('LIVE_TYPE') == 'wowza') {
                $endPoint = '/live_streams/' . $video->stream_id;
                $response = $this->handleWowzaResponse('delete', $endPoint, '', '');
            }
            if ($video->is_hls == 0 && !empty($video->stream_id) && getenv('LIVE_TYPE') == 'antmedia') {
                $endPoint = $video->stream_id . '/stop';
                $body = [];
                $antmediaResponse = $this->handleAntMediaResponse('post', $endPoint, $body);
            }
        }
    }
    /**
     * Method to update live stream record confirmation status
     *
     * @param $recordConfirmationStatus integer
     * @param $liveStreamId integer
     * @return void
     */
    public function updateLivestreamRecordingsStatus($recordConfirmationStatus, $liveStreamVideoid, $liveStreamID)
    {
        $data = 'Complete';
        if ($recordConfirmationStatus == 1) {
            $data = 'recording';
        }
        $this->liveStream->where('id', $liveStreamVideoid)->update([
            'live_recording_confirmation' => $recordConfirmationStatus,
            'liveStatus' => $data,
        ]);

        if ($recordConfirmationStatus == 0 && getenv('LIVE_TYPE') == 'wowza') {
            $endPoint = '/transcoders/' . $liveStreamID . '/recordings';
            $dataParam = 'recordings';
            $wowzaRecordingsData = $this->handleWowzaResponse('get', $endPoint, '', $dataParam);
            if (!empty($wowzaRecordingsData)) {
                $wowzaRecordingsData = end($wowzaRecordingsData);
                $liveVideoRecordings = $this->live_video_recordings;
                $liveVideoRecordings->live_video_id = $liveStreamVideoid;
                $liveVideoRecordings->live_video_recording_id = $wowzaRecordingsData['id'];
                $liveVideoRecordings->status = -2;
                $liveVideoRecordings->save();
            }
        }
    }
    /**
     * Method to get the status of livestream
     *
     * @return boolean
     */
    public function statusLiveStreamRepository()
    {
        $response = '';
        $liveStreamId = $this->liveStream->where('id', $this->request->id)->get()->first();

        try {
            if (getenv('LIVE_TYPE') == 'wowza') {
                $endPoint = '/live_streams/' . $liveStreamId->stream_id . '/state/';
                $response = $this->handleWowzaResponse('get', $endPoint, '', 'live_stream.state');
            }
            if (getenv('LIVE_TYPE') == 'antmedia') {
                $response = 'stopped';
            }
            if ($response == 'started' || $response == 'stopped') {
                $this->updateLiveStreamStatus($response, $this->request->id);
            }
        } catch (RequestException $e) {
            $response = $this->handleLivestreamException($e);
        }
        return $response;
    }
    /**
     * Method to get the status of livestream
     *
     * @return boolean
     */
    public function statusLiveStreamAll()
    {
        $client = new \GuzzleHttp\Client();
        $liveStreams = $this->liveStream->where('username', '!=', '')->get();
        foreach ($liveStreams as $liveStreamId) {
            try {
                $responce = $client->get((getenv('WOWZA_API_PRODUCTION_URL') . '/live_streams/' . $liveStreamId->stream_id . '/state/'), [
                    'headers' => [
                        'wsc-api-key' => getenv('WOWZA_CLOUD_API_KEY'),
                        'wsc-access-key' => getenv('WOWZA_CLOUD_ACCESS_KEY'),
                        'Content-Type' => 'application/json',
                    ],
                ]);
                $wowzaResponce = array_get(json_decode($responce->getBody(), 1), 'live_stream.state');
                if ($wowzaResponce == 'starting' || $wowzaResponce == 'started' || $wowzaResponce == 'stopped') {
                    Video::where('id', $liveStreamId->id)->update([
                        'liveStatus' => $wowzaResponce,
                    ]);
                }
            } catch (RequestException $e) {
                if (array_get(json_decode($e->getResponse()->getBody(), 1), 'meta.message') == "The requested resource has been deleted.") {
                    Video::where('id', $liveStreamId->id)->delete();
                }
            }
        }
    }

    /**
     * Function to save categories of a video in the database.
     *
     * @param integer $id
     * The id of the video whose categories are being saved.
     */
    public function saveVideoCategories($video)
    {
        $id = $video->id;
        $this->livevideoCategory = new VideoCategory();
        $this->livevideoSeason = new VideoSeason();
        $this->livevideoCategory->where(StringLiterals::VIDEOID, $id)->delete();

        if ($this->request->has('presenter') && !empty($this->request->presenter)) {
            $video->presenter = $this->request->presenter;
        }

        $video->audio_language = $this->request->audio_language;
        $video->save();

        if ($this->request->has('search_tag') && !empty($this->request->search_tag)) {
            foreach ($this->request->search_tag as $value) {
                $tagInfo = $this->tag->where('name', $value['text'])->first();
                if (empty($tagInfo)) {
                    $tagInfo = new Tag();
                    $tagInfo->name = $value['text'];
                    $tagInfo->save();
                }
                $tagInfo->videos()->attach($id);
            }
        }

        if ($this->request->has('category') && !empty($this->request->category)) {


            // $categoryId = $this->request->category;
            // $this->livevideoCategory = new VideoCategory();
            // $this->livevideoCategory->video_id = $id;
            // $this->livevideoCategory->category_id = $categoryId;
            // $this->livevideoCategory->save();

            $cat = $this->request->category;
            $arrlength = count($cat);
            for ($x = 0; $x < $arrlength; $x++) {
                $categoryId = $this->request->category;
                $this->livevideoCategory = new VideoCategory();
                $this->livevideoCategory->video_id = $id;
                $this->livevideoCategory->category_id = $cat[$x];
                $this->livevideoCategory->save();
            }
        }

        if ($this->request->has('group') && !empty($this->request->group)) {
            $group = $this->request->group;
            $category = $this->request->category;
            $category = Category::find($category);
            $this->collectionVideo = new CollectionVideo();
            $this->collectionVideo->video_id = $id;
            $this->collectionVideo->group_id = $group;
            $this->collectionVideo->parent_cateogry_id = $category->parent_id;
            $this->collectionVideo->save();
        }
        if ($this->request->has('season') && !empty($this->request->season)) {
            $seasonId = $this->request->season;
            $this->livevideoSeason = new VideoSeason();
            $this->livevideoSeason->video_id = $id;
            $this->livevideoSeason->season_id = $seasonId;
            $this->livevideoSeason->save();
        }

        if ($this->request->has('ads') && !empty($this->request->ads)) {
            $adsId = $this->request->ads;
            $this->videoAds = new VideoAds();
            $this->videoAds->video_id = $id;
            $this->videoAds->ads_id = $adsId;
            $this->videoAds->save();
        }

        // if($video->is_notify) {
        //     $notifyObj = new NotificationRepository();
        //     $related = [];
        //     $notifyObj->notify('video', $video->id,  $related);
        // }

    }

    public function saveRadioCategories($video)
    {
        $id = $video->id;
        $this->livevideoCategory = new VideoCategory();
        $this->livevideoSeason = new VideoSeason();
        $this->livevideoCategory->where(StringLiterals::VIDEOID, $id)->delete();

        if ($this->request->has('presenter') && !empty($this->request->presenter)) {
            $video->presenter = $this->request->presenter;
        }

        $video->audio_language = $this->request->audio_language;
        $video->save();

        if ($this->request->has('search_tag') && !empty($this->request->search_tag)) {
            foreach ($this->request->search_tag as $value) {
                $tagInfo = $this->tag->where('name', $value['text'])->first();
                if (empty($tagInfo)) {
                    $tagInfo = new Tag();
                    $tagInfo->name = $value['text'];
                    $tagInfo->save();
                }
                $tagInfo->videos()->attach($id);
            }
        }

        if ($this->request->has('category') && !empty($this->request->category)) {

            $cat = $this->request->category;
            $arrlength = count($cat);
            for ($x = 0; $x < $arrlength; $x++) {
                $categoryId = $this->request->category;
                $this->livevideoCategory = new VideoCategory();
                $this->livevideoCategory->video_id = $id;
                $this->livevideoCategory->category_id = $cat[$x];
                $this->livevideoCategory->save();
            }
        }

        if ($this->request->has('group') && !empty($this->request->group)) {
            $group = $this->request->group;
            $category = $this->request->category;
            $category = Category::find($category);
            $this->collectionVideo = new CollectionVideo();
            $this->collectionVideo->video_id = $id;
            $this->collectionVideo->group_id = $group;
            $this->collectionVideo->parent_cateogry_id = $category->parent_id;
            $this->collectionVideo->save();
        }
        if ($this->request->has('season') && !empty($this->request->season)) {
            $seasonId = $this->request->season;
            $this->livevideoSeason = new VideoSeason();
            $this->livevideoSeason->video_id = $id;
            $this->livevideoSeason->season_id = $seasonId;
            $this->livevideoSeason->save();
        }

        if ($this->request->has('ads') && !empty($this->request->ads)) {
            $adsId = $this->request->ads;
            $this->videoAds = new VideoAds();
            $this->videoAds->video_id = $id;
            $this->videoAds->ads_id = $adsId;
            $this->videoAds->save();
        }

        // if($video->is_notify) {
        //     $notifyObj = new NotificationRepository();
        //     $related = [];
        //     $notifyObj->notify('video', $video->id,  $related);
        // }

    }

    /**
     * Method to handle the wowza response using Guzzle Client
     *
     * @param $method string
     * @param $endPoint string
     * @param $dataParam string
     */
    public function handleAntMediaResponse($method, $endPoint, $postData = array(), $dataParam = '')
    {
        $client = new \GuzzleHttp\Client();
        $response = '';
        $URL = getenv('ANTMEDIA_API_PRODUCTION_URL') . $endPoint;
        $headers = [
            'Accept' => 'application/json, text/plain, */*',
            'Host' => env('WEBRTC_HOST', 'webrtc.vplayed.com'),
            'Referer' => 'https://' . env('WEBRTC_HOST', 'webrtc.vplayed.com') . '/',
            'Content-Type' => 'application/json',
        ];
        try {
            switch ($method) {
                case 'get':
                    $response = $client->get(($URL), ['headers' => $headers]);
                    break;
                case 'put':
                    $response = $client->put(($URL), ['headers' => $headers]);
                    break;
                case 'delete':
                    $response = $client->delete(($URL), ['headers' => $headers]);
                    break;
                case 'post':
                    $response = $client->post(($URL), ['headers' => $headers, 'body' => json_encode($postData)]);
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
            app('log')->error(' ###File : ' . $exception->getFile() . ' ##Line : ' . $exception->getLine() . ' #Error : ' . $exception->getMessage());
        }
    }

    public function livestreamStatus()
    {
        \Log::info("webhook update");
        \Log::info($this->request->all());
        $data = ['info' => 'success'];

        // $this->liveStream->where();

        return json_encode($data);
    }

    public function togglePublishNow($id)
    {
        $liveStream = $this->liveStream->find($id);

        if ($liveStream) {
            $liveStream->is_active = $liveStream->is_active == 1 ? 0 : 1;
            $liveStream->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Live Stream Published Successfully.'
        ]);
    }
}
