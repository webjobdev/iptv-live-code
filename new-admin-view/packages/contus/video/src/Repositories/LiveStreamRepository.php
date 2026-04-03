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

use Contus\Base\Repository as BaseRepository;
use Illuminate\Database\QueryException;
use Contus\Video\Models\Video;
use Contus\Video\Models\Category;
use Contus\Video\Models\VideoCategory;
use Contus\Video\Models\VideoSeason;
use Contus\Video\Models\CollectionVideo;
use Contus\Video\Models\Group;
use Contus\Base\Helpers\StringLiterals;
use Contus\Notification\Repositories\NotificationRepository;
use GuzzleHttp\Psr7;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Cache;
use Contus\Video\Models\LiveVideoRecordings;
use Contus\Video\Repositories\AWSUploadRepository;
use Contus\Base\Repositories\UploadRepository;
use Contus\Video\Models\TranscodedVideo;
use Contus\Video\Models\VideoPreset;
use Contus\Video\Models\Tag;
use Contus\Video\Models\VideoAds;

class LiveStreamRepository extends BaseRepository {
    /**
      * Class initializer
      *
      * @return void
      */
    public function __construct(AWSUploadRepository $awsRepository) {
         parent::__construct ();
         $this->liveStream = new Video();
         $this->tag = new Tag ();
         $this->collectionVideo = new CollectionVideo ();
         $this->live_video_recordings = new LiveVideoRecordings();
         $this->awsRepository = new AWSUploadRepository (new TranscodedVideo (), new VideoPreset ());
       
    }

        /**
      * This method is use to trigger wowza live stream create api.
      *
      * @see \\Contus\Base\Contracts\ResourceInterface::store()
      *
      * @return boolean
      */
    public function store() {
         return $this->createLiveStream ( $this->request->all () );
    }
    /**
     * Method to handle the wowza response using Guzzle Client
     * 
     * @param $method string
     * @param $endPoint string
     * @param $dataParam string
     */
    public function handleWowzaResponse($method, $endPoint, $postData = array(), $dataParam = ''){
        $client = new \GuzzleHttp\Client ();
        $response = '';
        $URL = getenv ( 'WOWZA_API_PRODUCTION_URL' ).$endPoint;
        $headers = [ 
                    'wsc-api-key' => getenv ( 'WOWZA_CLOUD_API_KEY' ),
                    'wsc-access-key' => getenv ( 'WOWZA_CLOUD_ACCESS_KEY' ),
                    'Content-Type' => 'application/json'  
                ];
        try{
            switch ($method){
                case 'get':
                    $response = $client->get( ($URL),['headers' => $headers]);
                    break;
                case 'put':
                    $response = $client->put( ($URL), ['headers' => $headers]);
                    break;
                case 'delete':
                    $response = $client->delete( ($URL), ['headers' => $headers]);
                    break;
                case 'post':
                    $response = $client->post ( ($URL), ['headers' => $headers,'body' =>json_encode ( $postData ) ]);
                    break;
                default:
                break;
            }
            if(!empty($method) && $method == 'post'){
                $result = json_decode ( $response->getBody (), 1 );
            }else{
                $result = array_get ( json_decode ( $response->getBody (), 1 ), $dataParam);
            }
            return $result;
        }catch(RequestException $exception){
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
    public function updateLiveStreamStatus($liveStatus,$liveStreamid){
        $this->liveStream->where ( 'id', $liveStreamid)->update ( [ 
            'liveStatus' => $liveStatus ,
            'scheduledStartTime' => date ( "Y-m-d H:i:s"),
            'job_status' => 'Complete',
            'transcode_status' => 'Complete',

        ] );  
    }
    /**
     * Method to handle exception for both start and stop of live stream
     * @param array $e
     */
    public function handleLivestreamException($e){
        if (array_get ( json_decode ( $e->getResponse ()->getBody (), 1 ), 'meta.message' ) == "The requested resource has been deleted.") {
            $this->liveStream->where ( 'id', $this->request->id )->delete ();
            return 'deleted';
        }
        return array_get ( json_decode ( $e->getResponse ()->getBody (), 1 ), 'meta.message' );
    }
     /**
      * /**
      * This method is used to create live stream and save the details in db
      *
      * @param array $requestData          
      * @return boolean
      */
    public function createLiveStream($requestData) {
      
       
        if(!empty($requestData['liveType']) && $requestData['liveType'] == 'hls'){
            $this->setRules ( ['hls'=>'required|url','title'=>'required','description'=>'required', 'category' => 'required'] );
            $this->_validate ();
            $liveStream = $this->liveStream;
            $liveStream->stream_id = 'wowza';
            $liveStream->encoder_type = 'wowza';
            $liveStream->source_url = 'wowza' ;
            $liveStream->stream_name = 'wowza' ;
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
               $fileName =  $liveStream->getImageBaseName( $thumbUrl [count($thumbUrl) - 1]);
               $folderName = config("contus.base.image.thumbnail.s3_location");
               $localStoragePath = public_path().DIRECTORY_SEPARATOR.config("contus.base.image.thumbnail.temporary_image_storage_path");
               $s3BucketImgFilename = $this->uploadTos3Bucket($fileName,$folderName,$localStoragePath);
               $s3BucketImgURL = $folderName.$s3BucketImgFilename;
               $liveStream->thumbnail_image =  $s3BucketImgURL;
           }
           if (isset($requestData['poster_image']) && $requestData['poster_image'] != '') {
            $thumbUrl = explode("/", $requestData['poster_image']);
            $fileName =  $liveStream->getImageBaseName($thumbUrl [count($thumbUrl) - 1]);
            $folderName = config("contus.base.image.posters.s3_location");
            $localStoragePath = public_path().DIRECTORY_SEPARATOR.config("contus.base.image.posters.temporary_image_storage_path");
            $s3BucketImgFilename = $this->uploadTos3Bucket($fileName,$folderName,$localStoragePath);
            $s3BucketImgURL = $folderName.$s3BucketImgFilename;
            $liveStream->poster_image =  $s3BucketImgURL;
        }
            
            
            // NOTE : To format the scheduled time into db format
            $date = (!empty($requestData['scheduled_time'])) ? $requestData['scheduled_time'] : '';
            $liveStream->scheduledStartTime = $date; // format_schedule_date($date);


            $liveStream->description = $requestData['description'];
            $liveStream->hls_playlist_url = isset($requestData ['hls']) ? $requestData ['hls'] :"" ;
            
            $liveStream->broadcast_location ='wowza' ;
            $liveStream->creator_id = 1;
            $liveStream->updator_id = 1;
            
            $liveStream->is_active = $requestData['is_active'] ? 1 : 0;
            $liveStream->is_notify = $requestData['is_notify'] ? 1 : 0;
            $liveStream->is_premium = $requestData['is_premium'] ? 1 : 0;
            $liveStream->save ();        
            $this->saveVideoCategories($liveStream);

            return 'success';
        }else{
            $this->setRules ( ['aspect_ratio'=>'required','title'=>'required','description'=>'required', 'category' => 'required'] );
            $this->_validate ();
        }
        $aspectRatio = $requestData ['aspect_ratio'];
        $aspectRatio = explode ( "X", $aspectRatio );
        $aspect_ratio_width = $aspectRatio [0];
        $aspect_ratio_height = $aspectRatio [1];
        
        /**    
         * /**
        * This method is used for encoder is wowza_gocoder
        *        
        * @return array
        */
        //event_name
        $requestData ['encoder_type'] = 'push';
        $requestData ['encoder'] = 'other_rtmp';
        $requestData ['broadcast_location'] = env('WOWZA_BROADCAST_LOCATION','asia_pacific_singapore');
        if ($requestData ['encoder_type'] == 'push') {
            $body = [ 
                'live_stream' => [
                    'name' => $requestData['title'],
                    'transcoder_type' => 'transcoded',
                    'billing_mode' => 'pay_as_you_go',
                    'broadcast_location' => $requestData ['broadcast_location'],
                    'encoder' => $requestData ['encoder'],
                    "use_stream_source" => 'false',
                    'delivery_method' => $requestData ['encoder_type'],
                    'aspect_ratio_width' => $aspect_ratio_width,
                    'aspect_ratio_height' => $aspect_ratio_height,
                    'player_type' => 'wowza_player',
                    'player_responsive' => 'true',
                    'recording' => 'true',
                    'video_fallback' => 'true'
                ] 
            ];
        }
      
        $client = new \GuzzleHttp\Client ();
        try {
            $wowzaResponse  = $this->handleWowzaResponse('post', '/live_streams', $body);
            $liveStream = $this->liveStream;
            $liveStream->stream_id = $wowzaResponse ['live_stream'] ['id'];
            $liveStream->encoder_type = $requestData ['encoder_type'];
            $liveStream->source_url = isset($wowzaResponse ['live_stream'] ['source_connection_information'] ['primary_server']) ? $wowzaResponse ['live_stream'] ['source_connection_information'] ['primary_server'] :"" ;
            $liveStream->stream_name = isset($wowzaResponse ['live_stream'] ['source_connection_information'] ['stream_name']) ? $wowzaResponse ['live_stream'] ['source_connection_information'] ['stream_name'] :"" ;
            $liveStream->username = isset($wowzaResponse ['live_stream'] ['source_connection_information'] ['username']) ? $wowzaResponse ['live_stream'] ['source_connection_information'] ['username'] :"" ;
            $liveStream->password = isset($wowzaResponse ['live_stream'] ['source_connection_information'] ['password']) ? $wowzaResponse ['live_stream'] ['source_connection_information'] ['password'] :"" ;
            $liveStream->liveStatus = 'ready';
            $liveStream->job_status = 'Complete';
            $liveStream->transcode_status = 'Complete';
            $liveStream->is_live = '1';
            $liveStream->live_recorded_status = 0;
            $liveStream->title = $requestData['title'];
            

            // NOTE : To format the scheduled time into db format
            $date = (!empty($requestData['scheduled_time'])) ? $requestData['scheduled_time'] : '';
            $liveStream->scheduledStartTime = $date; //format_schedule_date($date);
           
            
            $liveStream->description = $requestData['description'];
            $liveStream->hls_playlist_url = isset($wowzaResponse ['live_stream'] ['player_hls_playback_url']) ? $wowzaResponse ['live_stream'] ['player_hls_playback_url'] :"" ;
            $liveStream->broadcast_location = isset($wowzaResponse ['live_stream'] ['broadcast_location']) ? $wowzaResponse ['live_stream'] ['broadcast_location'] :"" ;
            $liveStream->presenter = isset($requestData ['presenter']) ? $requestData ['presenter'] :"" ;
            $liveStream->creator_id = 1;
            $liveStream->updator_id = 1;


            if (isset($requestData['thumbnail_image']) && $requestData['thumbnail_image'] != '') {
                 $thumbUrl = explode("/", $requestData['thumbnail_image']);

                $fileName =  $liveStream->getImageBaseName( $thumbUrl [count($thumbUrl) - 1]);
                $folderName = config("contus.base.image.thumbnail.s3_location");
                $localStoragePath = public_path().DIRECTORY_SEPARATOR.config("contus.base.image.thumbnail.temporary_image_storage_path");
                $s3BucketImgFilename = $this->uploadTos3Bucket($fileName,$folderName,$localStoragePath);
                $s3BucketImgURL = $folderName.$s3BucketImgFilename;
                $liveStream->thumbnail_image =  $s3BucketImgURL;
            }
            if (isset($requestData['poster_image']) && $requestData['poster_image'] != '') {
                $thumbUrl = explode("/", $requestData['poster_image']);
                $fileName =  $liveStream->getImageBaseName($thumbUrl [count($thumbUrl) - 1]);
                $folderName = config("contus.base.image.posters.s3_location");
                $localStoragePath = public_path().DIRECTORY_SEPARATOR.config("contus.base.image.posters.temporary_image_storage_path");
                $s3BucketImgFilename = $this->uploadTos3Bucket($fileName,$folderName,$localStoragePath);
                $s3BucketImgURL = $folderName.$s3BucketImgFilename;
                $liveStream->poster_image =  $s3BucketImgURL;
            }
            // if (isset($requestData[StringLiterals::POSTERIMAGE])) {
            //     $posterUrl = explode("/", $requestData['poster_image']);
            //     $liveStream->poster_image = $posterUrl [count($posterUrl) - 1];
            // }
            $liveStream->is_active = $requestData['is_active'] ? 1 : 0;
            $liveStream->is_notify = $requestData['is_notify'] ? 1 : 0;
            $liveStream->is_premium = $requestData['is_premium'] ? 1 : 0;
            $liveStream->aspect_ratio = (!empty($requestData['aspect_ratio'])) ? $requestData['aspect_ratio'] : '';
            $liveStream->save ();
        
            $this->saveVideoCategories($liveStream);

            return 'success';
        } catch ( RequestException $e ) {
            return $e->getMessage ().$e->getLine();
        }
    }
    /**
      * Method to start the livestream         
      * @return boolean
      */
      public function startLiveStreamRepository() {
            $response = '';
            $liveStreamId = $this->liveStream->where ( 'id', $this->request->id )->get ()->first ();
            if(!empty($liveStreamId) && $liveStreamId->is_hls == 0){
                try {
                    $this->setRule ( 'id', 'required' )->_validate ();
                    $endPoint = '/live_streams/' . $liveStreamId->stream_id . '/start/';
                    $response  = $this->handleWowzaResponse('put', $endPoint,'','live_stream.state');
                    $this->updateLiveStreamStatus($response,$this->request->id);
                } catch ( RequestException $e ) {
                    $response = $this->handleLivestreamException($e);
                }
            }else{
                $this->updateLiveStreamStatus('started',$this->request->id);
                $response = 'Live video started';
            }
            return $response;
        }
    /**
      * Method to stop the livestream
      *         
      * @return boolean
      */
    public function stopLiveStreamRepository() {
        $response = '';
        $liveStreamStopId = $this->liveStream->where ( 'id', $this->request->id )->get ()->first ();
        if(!empty($liveStreamStopId) && $liveStreamStopId->is_hls == 0){
            try {
                $this->setRule ( 'id', 'required' )->_validate ();
                $endPoint = '/live_streams/' . $liveStreamStopId->stream_id . '/stop/';
                $response  = $this->handleWowzaResponse('put', $endPoint,'','live_stream.state');
                $response = ($this->request->record_status == 1)?'recording':$response;
                $this->updateLiveStreamStatus($response,$this->request->id);
                $this->updateLivestreamRecordingsStatus($this->request->record_status,$this->request->id,$liveStreamStopId->stream_id);
            } catch ( RequestException $e ) {
                $response = $this->handleLivestreamException($e);
            }
        }else{
            $this->updateLiveStreamStatus('stopped',$this->request->id);
            $response = 'Live video stopped';
        }
        return $response;
    }
    /**
     * Method to delete livestream
     * 
     * @param $videoIds array
     */
    public function deleteLivestream($videoIds) {
        foreach($videoIds as $id){
             $video =   $this->liveStream->select('is_hls','stream_id')->where ( 'id',$id)->get()->first();  
                if($video->is_hls == 0 && !empty($video->stream_id)){
                    $endPoint = '/live_streams/' . $video->stream_id;
                    $response  = $this->handleWowzaResponse('delete', $endPoint,'','');
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
    public function updateLivestreamRecordingsStatus($recordConfirmationStatus,$liveStreamVideoid,$liveStreamID){
        $this->liveStream->where ( 'id', $liveStreamVideoid)->update ( [ 
            'live_recording_confirmation' => $recordConfirmationStatus
        ] );
        if($recordConfirmationStatus == 0){
            $endPoint = '/transcoders/'.$liveStreamID.'/recordings';
            $dataParam = 'recordings';
            $wowzaRecordingsData = $this->handleWowzaResponse('get',$endPoint, '', $dataParam);
            if(!empty($wowzaRecordingsData)){
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
    public function statusLiveStreamRepository() {
        $response = '';
        $liveStreamId = $this->liveStream->where ( 'id', $this->request->id )->get ()->first ();
        try {
            $endPoint = '/live_streams/' . $liveStreamId->stream_id . '/state/';
            $response  = $this->handleWowzaResponse('get', $endPoint,'','live_stream.state');
        if ($response == 'started' || $response == 'stopped') {
            $this->updateLiveStreamStatus($response,$this->request->id);
        }             
        } catch ( RequestException $e ) {
            $response = $this->handleLivestreamException($e);
        }
        return $response;
    }
    /**
    * Method to get the status of livestream
    *
    * @return boolean
    */
    public function statusLiveStreamAll() {
      $client = new \GuzzleHttp\Client ();
      $liveStreams = $this->liveStream->where ( 'username','!=', '' )->get ();
      foreach ($liveStreams as $liveStreamId) {
        try {
          $responce = $client->get ( (getenv ( 'WOWZA_API_PRODUCTION_URL' ) . '/live_streams/' . $liveStreamId->stream_id . '/state/'), [ 
                          'headers' => [ 
                                   'wsc-api-key' => getenv ( 'WOWZA_CLOUD_API_KEY' ),
                                   'wsc-access-key' => getenv ( 'WOWZA_CLOUD_ACCESS_KEY' ),
                                   'Content-Type' => 'application/json' 
                          ] 
                 ] );
          $wowzaResponce = array_get ( json_decode ( $responce->getBody (), 1 ), 'live_stream.state' );
          if ($wowzaResponce == 'starting' || $wowzaResponce == 'started' || $wowzaResponce == 'stopped'){
            Video::where ( 'id', $liveStreamId->id )->update ( [ 
              'liveStatus' => $wowzaResponce
            ] );
          }
        } catch ( RequestException $e ) {
           if (array_get ( json_decode ( $e->getResponse ()->getBody (), 1 ), 'meta.message' ) == "The requested resource has been deleted.") {
              Video::where ( 'id', $liveStreamId->id )->delete ();
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
        $this->livevideoCategory = new VideoCategory ();
        $this->livevideoSeason = new VideoSeason ();
        $this->livevideoCategory->where(StringLiterals::VIDEOID, $id)->delete();

        if($this->request->has('presenter') && !empty($this->request->presenter)) {
            $video->presenter = $this->request->presenter;
        }

        $video->audio_language = $this->request->audio_language;
        $video->save();

        if($this->request->has('search_tag') && !empty($this->request->search_tag)) {
            foreach ($this->request->search_tag as $value) {
                $tagInfo = $this->tag->where('name', $value['text'])->first();
                if (empty ($tagInfo)) {
                    $tagInfo = new Tag ();
                    $tagInfo->name = $value['text'];
                    $tagInfo->save();
                }
                $tagInfo->videos()->attach($id);
            }
        }

        if($this->request->has('category') && !empty($this->request->category)) {
            $categoryId = $this->request->category;
            $this->livevideoCategory = new VideoCategory ();
            $this->livevideoCategory->video_id = $id;
            $this->livevideoCategory->category_id = $categoryId;
            $this->livevideoCategory->save();
        }

        if($this->request->has('group') && !empty($this->request->group)) {
            $group = $this->request->group;
            $category = $this->request->category;
            $category = Category::find($category);
            $this->collectionVideo = new CollectionVideo ();
            $this->collectionVideo->video_id = $id;
            $this->collectionVideo->group_id = $group;
            $this->collectionVideo->parent_cateogry_id = $category->parent_id;
            $this->collectionVideo->save();
        }
        if($this->request->has('season') && !empty($this->request->season)) {
            $seasonId = $this->request->season;
            $this->livevideoSeason = new VideoSeason ();
            $this->livevideoSeason->video_id = $id;
            $this->livevideoSeason->season_id = $seasonId;
            $this->livevideoSeason->save();
        }

        if($this->request->has('ads') && !empty($this->request->ads)) {
            $adsId                      = $this->request->ads;
            $this->videoAds             = new VideoAds ();
            $this->videoAds->video_id   = $id;
            $this->videoAds->ads_id     = $adsId;
            $this->videoAds->save();
        }

        if($video->is_notify) {
            $notifyObj = new NotificationRepository();
            $notifyObj->notify('video', $video->id);
        }
    }
}