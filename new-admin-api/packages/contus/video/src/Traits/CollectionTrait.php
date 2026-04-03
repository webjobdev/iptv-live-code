<?php

/**
 * CategoryTrait
 *
 * To manage the functionalities related to the Categories module from Categories Controller
 *
 * @vendor Contus
 *
 * @package Categories
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Traits;
use Contus\Video\Models\Video;
use Contus\Video\Models\VideoMetaData;
use Contus\Video\Models\TranscoderTracking;
use Contus\Video\Models\Category;
use Contus\Video\Repositories\FrontVideoRepository;
use Contus\Base\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Contus\Video\Repositories\AWSUploadRepository;
use Contus\Video\Models\VideoPreset;
use Contus\Video\Models\TranscodedVideo;
use \Done\Subtitles\Subtitles;
use Contus\Video\Models\VideoAudioUploads;
use Contus\Video\Schedulers\VideosAudioTrackTranscodingScheduler;

use App\Support\GenerateSpriteImage;

trait CollectionTrait{
 
 /**
  * Repository function to delete custom thumbnail of a video.
  *
  * @param integer $id
  * The id of the video.
  * @return boolean True if the thumbnail is deleted and false if not.
  */
 public function deleteThumbnail($id){
  $video =new video();
  /**
   * Check if video id exists.
   */
  if (!empty ($id)) {
   $video = $video->findorfail($id);
   /**
    * Delete the thumbnail image using the thumbnail path field from the database.
    */
   $video->thumbnail_image = '';
   $video->thumbnail_path = '';
   $video->save();
   return true;
  } else {
   return false;
  }
 }
  
 /**
  * Repository function to delete subtitle of a video.
  *
  * @param integer $id
  * The id of the video.
  * @return boolean True if the subtitle is deleted and false if not.
  */
 public function deleteSubtitle($id)
 {
  $updatedSubtitle = [];
  $video =new video();
  
  /**
   * Check if video id exists.
   */
  if (!empty ($id)) {
   $video = $video->findorfail($id);
   $existingSubtitle = json_decode($video->subtitle,true);
   $currnetSubtitle = $this->request->subtitle;

   unset($existingSubtitle[array_search($currnetSubtitle['url'], array_column($existingSubtitle, 'url'))]);


   $this->aws = New AWSUploadRepository(New TranscodedVideo,New VideoPreset);
   if($this->aws->deleteFileFromS3Bucket($currnetSubtitle['url'])){
    $video->subtitle = json_encode(array_values($existingSubtitle));
   $video->save();
   }else{
    return false;
   }
   return $this->getSuccessJsonResponse(['response' => true]);;
  } else {
   return false;
  }
 }
 /**
  * Function to fetch all videos
  *
  * @return json
  */
 public function liveVideoNotification() {
  $fetch ['live'] = FrontVideoRepository::getLiveVideoNotification ();
  return Controller::getSuccessJsonResponse ( [ 'message' => trans ( 'video::videos.fetch.success' ),'response' => $fetch ] );
 }
 /**
  * Funtion to send the related search key for search funtionlaity
  *
  * @return json
  */
 public function searchRelatedVideos() {
  $fetch ['videos'] = FrontVideoRepository::getallVideo ( false );
  if (array_filter ( $fetch )) {
   return Controller::getSuccessJsonResponse ( [ 'message' => trans ( 'video::videos.fetch.success' ),'response' => $fetch ] );
  } else {
   return Controller::getErrorJsonResponse ( [ ], trans ( 'video::videos.fetch.error' ) );
  }
 }
 /**
  * Function to add the video play tracking list
  *
  * @param id|string $slug
  */
 public function videoPlayTracker($slug) {
  (FrontVideoRepository::videoPlayTracker ( $slug )) ? Controller::getSuccessJsonResponse ( [ 'message' => trans ( 'video::videos.fetch.success' ) ] ) : Controller::getErrorJsonResponse ( [ ], trans ( 'video::videos.fetch.error' ) );
 }
 
 
 /**
  * This function used to get the all the scheduled and recorded videos
  */
 public function AllLiveVideos() {
  $fetch ['all_live_videos'] = FrontVideoRepository::getAllLiveVideos ();
  if (array_filter ( $fetch )) {
   return Controller::getSuccessJsonResponse ( [ 'message' => trans ( 'video::videos.fetch.success' ),'response' => $fetch ] );
  } else {
   return Controller::getErrorJsonResponse ( [ ], trans ( 'video::videos.fetch.error' ) );
  }
 }

    /**
     * Funtion to search Videos with respect to video title and description
     *
     * @return json
     */
    public function getSearachVideo() {

      $this->setRules(['search' => 'required', 'order'=>'sometimes|in:title', 'sort'=>'sometimes|in:asc,desc']);
      $this->validate($this->request, $this->getRules());

      $searchKey = $this->request->search;
      $video = $this->video->whereCustomer()->where(function($query) use ($searchKey) {
        $query->orwhere('slug', 'like', '%'.$searchKey.'%')
        ->orwhere('title', 'like', '%'.$searchKey.'%');
      });

      $fields = 'videos.id, videos.title, videos.slug, videos.description, videos.short_description, videos.thumbnail_image, videos.selected_thumb, videos.hls_playlist_url, count("video_id") as count, videos.id as is_favourite, videos.id as collection';

      

      $video->with(['categories.parent_category'])->leftJoin('recently_viewed_videos as f1', function ($j) {
          $j->on('videos.id', '=', 'f1.video_id');
      })->selectRaw($fields)->where('is_live', '==', 0)->groupBy('videos.id');

      $inputArray = $this->request->all();
      if(isset($inputArray['order']) && !empty($inputArray['order'])) {
        $video->orderBy($inputArray['order'], $inputArray['sort']);
      }
      else {
        $video->orderBy('id', 'desc');
      }

      return $video->paginate(config('access.perpage'));
    }
 
    /**
     * Function to get the top nth Categories
     * @param  integer $limit - Get the offset of the category to be fetched
     * @return [object]  categoryObject
     */
    public function getTopNthCategory($limit = 0) {
        $catObj = new Category();
        return $catObj->where('parent_id', 0)->where('level', 0)->where('is_active', 1)->orderBy('category_order', 'asc')->skip($limit)->take(1)->first();
    }

    public function fetchRecentVideos($fields, $video) {
      $userId = (auth()->user()) ? auth()->user()->id : 0;
      $videoInfo = $video->with(['categories.parent_category'])
        ->leftJoin('recently_viewed_videos as f1', function ($j) {
          $j->on('videos.id', '=', 'f1.video_id');
        })->where('f1.customer_id', '=', $userId)->selectRaw($fields)->where('is_live', '==', 0)->groupBy('videos.id')->orderBy('id', 'desc')->paginate(config('access.perpage'));

      return $videoInfo->toArray();
    }
 
    /**
     * Function to get all video to frontend with filters and search
     *
     * @vendor Contus
     *
     * @package video
     * @return array
     */
    public function searchAllVideo()
    {
        $result['error'] = false;
        $result['message'] = '';
        $inputArray = $this->request->all();

        $this->setRules(['order' => 'sometimes|in:title', 'sort' => 'sometimes|in:asc,desc']);
        $this->validate($this->request, $this->getRules());

        $fields = 'videos.id, videos.title, videos.slug, videos.description, videos.short_description, videos.thumbnail_image, videos.selected_thumb, videos.hls_playlist_url, videos.video_duration, videos.id as is_favourite, videos.id as collection, videos.poster_image';

          $this->video = $this->video->whereCustomer()->where('is_live', '!=', 1)->has('categories')->with('categories');

          $this->video = $this->constructSearchQuery($this->video);
          
          $video = $this->video->leftJoin('favourite_videos as f1', function ($j) {
              $j->on('videos.id', '=', 'f1.video_id')->on('f1.customer_id', '=', DB::raw((auth()->user()) ? auth()->user()->id : 0));
          })->selectRaw($fields)->groupBy('videos.id');

          if ($this->request->has('video_id')) {
              $video = $video->where('videos.id', '!=', $this->request->video_id);
          }

        $video = $video->paginate(9)->toArray();

          $paramArray = array_filter($inputArray);
        if((!isset($inputArray['page']) || $inputArray['page'] <= 1) && (!isset($paramArray['category'])) && (!isset($paramArray['genre']))) {
          $genreInfo    = $this->fetchPopularGenre(false);
          unset($genreInfo['category_name']);
          $final['genres'] = $genreInfo;
          $final['categories'] = $this->getChildrenCategory();
        }
        
        $final['videos'] = $video;
        $result['data'] = $final;
        return $result;
    }

    /**
     * Function to fetch child categories for the given main category
     * @return Object- Return child category object
     */
    public function getChildrenCategory() {
      $category = Category::With(['child_category' => function($query) {
        $query->selectRaw('*, id as video_count');
      }])->where($this->getKeySlugorId(), $this->request->main_category);
      return $category->first();
    }

    /**
     * Function to construct search query based on the requested params
     * @param  Object $videoObj Video Object
     * @return Object Video Object
     */
    public function constructSearchQuery($videoObj) {
      $inputArray = $this->request->all();

      if(!empty($inputArray)) {
        foreach($inputArray as $inputKey=>$inputValue) {
          if($inputValue != '') {
            switch($inputKey) {
              case 'search':
                $videoObj = $videoObj->where('title', 'like', '%' . $this->request->search . '%');
                break;
              case 'main_category':
                $videoObj = $videoObj->whereHas('categories.parent_category', function ($q) {
                    $q->where($this->getKeySlugorId(), $this->request->main_category);
                });
                break;
              case 'category':
                $categoryArray = explode(',', $this->request->category);
                $videoObj = $videoObj->whereHas('categories', function ($q) use($categoryArray) {
                    $q->whereIn('categories.'.$this->getKeySlugorId(), $categoryArray);
                });
                break;
              case 'genre':
                $genreArray = explode(',', $this->request->genre);
                $videoObj = $videoObj->whereHas('collections', function ($q) use($genreArray) {
                    $q->whereIn('groups.'.$this->getKeySlugorId(), $genreArray);
                });
                break;
              default:
                break;
            }
          }
        }

        if(isset($inputArray['order']) && !empty($inputArray['order'])) {
          $videoObj = $videoObj->orderBy($inputArray['order'], $inputArray['sort']);
        }
        else {
          $videoObj = $videoObj->orderBy('video_order', 'desc');
        }
      }
      return $videoObj;
    }

    /**
     * Function to clear the video view history
     * @return Array
     */
    public function clearVideoView() {
      $result['error'] = false;
      $result['message'] = '';
      $videoIds = [];

      $videoIds = $this->fetchVideoIds();
      try {
        if(!empty($videoIds)) {
          auth()->user()->recentlyViewed()->detach($videoIds);
        }
        else {
          auth()->user()->recentlyViewed()->detach();
        }
      }
      catch (\Exception $e) {
        $result['error'] = true;
        $result['message'] = trans('video::videos.fetch.error');
      }

    }

    /**
     * Function to fetch video ids for the given slug
     * @param  string $slug - video slug
     * @return Array - Video id Array
     */
    public function fetchVideoIds() {
      $videoIds = [];
      if($this->request->has('video_id') && !empty($this->request->video_id)) {
        $videoIds = explode(',', $this->request->video_id);
      }
      if(!isMobile()) {
        $videoIds = Video::whereIn('slug', $videoIds)->pluck('id')->toArray();
      }
      return $videoIds;
    }

    public function fetchLiveVideos() {
        try {
            $result['error']    = false;
            $result['message']  = '';
            $result['data']  = '';

            $fields = 'videos.id, videos.title, videos.slug, videos.description, videos.short_description, videos.thumbnail_image, videos.selected_thumb, videos.hls_playlist_url, videos.id as is_favourite, videos.id as collection, videos.poster_image,videos.is_live, videos.scheduledStartTime';

            $videos = $this->video->whereliveVideos()->whereRaw ( 'scheduledStartTime < "' . Carbon::now ()->now () . '" ' )->orderBy('scheduledStartTime', 'asc')->with(['categories.parent_category'])->selectRaw($fields)->get();

            $videoObj = new Video();
            $todayLive = $videoObj->whereliveVideos()->whereRaw ( 'scheduledStartTime > "' . Carbon::now ()->now ().'" ')->whereRaw ( 'scheduledStartTime < "' . Carbon::now ()->toDateString () . ' 23:59:59 "' )->orderBy('scheduledStartTime', 'asc')->with(['categories.parent_category'])->selectRaw($fields)->get();

            $upcomingLive = $this->fetchMoreLiveVideos();

            $videoInfo['current_live_videos'] = $videos->toArray();
            $videoInfo['todal_live_videos'] = $todayLive->toArray();
            $videoInfo['upcoming_live_videos'] = (!empty($upcomingLive['data'])) ? $upcomingLive['data']->toArray() : [];
            $result['data']  = $videoInfo;
        }
        catch (\Exception $e) {
            $result['error'] = true; 
            $result['message']    = $e->getMessage();
            $result['data']  = '';
        }
        return $result;
    }

    public function fetchMoreLiveVideos() {
        $fields = 'videos.id, videos.title, videos.slug, videos.description, videos.short_description, videos.thumbnail_image, videos.selected_thumb, videos.hls_playlist_url, videos.id as is_favourite, videos.id as collection, videos.poster_image,videos.is_live, videos.scheduledStartTime';
        
        try {
            $result['error']    = false;
            $result['message']  = '';
            $result['data']     = $this->video->whereliveVideos()->whereRaw ( 'scheduledStartTime > "' . Carbon::tomorrow().'" ')->with(['categories.parent_category'])->selectRaw($fields)->orderBy('scheduledStartTime', 'asc')->paginate();
        }
        catch (\Exception $e) {
            $result['error'] = true; 
            $result['message']    = $e->getMessage();
        }
        return $result;
    }

    public function getProgress() {
      $videoInfo  = [];
      $inputArray = $this->request->all();
      if(!empty($inputArray['video_ids'])) {
        $videoArray = $inputArray['video_ids'];
      }

      if(!empty($videoArray)) {
        $videoInfo = Video::selectRaw('id, job_status, upload_percentage')->whereIn('id', $videoArray)->get();
      }

      $result['video_info'] = $videoInfo;
      $result['transcode_info'] = $this->getTranscodedHours();
      return $this->getSuccessJsonResponse(['response' => $result]);
    }

    public function getTranscodedHours() {
      $totalSec = $totalMin = $remMin = $remSec = 0;
      $enableUpload = true;

      if(env('TRANSCODE_LIMIT', '') != '' && env('VIDEO_TRANSCODE_TYPE') == 'AWS') {
        $transObj = new TranscoderTracking();
        $minutes = TranscoderTracking::where('is_active', 1)->sum('minutes');
        $seconds = TranscoderTracking::where('is_active', 1)->sum('seconds');
        $totalMin = $minutes + floor($seconds / 60);
        $totalSec = ($seconds % 60);
        $remMin  = env('TRANSCODE_LIMIT') - $totalMin;
        $remSec  = ($remMin > 0 && $totalSec > 0) ? (60 - $totalSec) : 0;

        if($totalSec > 0 && $remMin > 0) {
          $remMin = ( $remMin - 1 );
        }

        $enableUpload = ($remMin > 0 || $remSec > 0) ? true : false;
      }

      $result['transLimit'] = (int) env('TRANSCODE_LIMIT');
      $result['enableUpload']   = $enableUpload;
      $result['totalMin']   = $totalMin;
      $result['totalSec']   = $totalSec;
      $result['remMin']   = $remMin;
      $result['remSec']   = $remSec;
      $result['remaining']  = $remMin .' Min and '.$remSec.' Sec';
      return $result;
    }

    public function postUploadSubTitles() {
      $files = $this->request->file();
      $id    = $this->request->input('id');
      $s3Client = $this->awsRepository->awsS3Client;
      $awsS3Bucket = env('AWS_BUCKET');
      $obj=[];
      $i=0;
      
      try {
        if(!empty($files)) {
          foreach($files as $key =>$file){
            $extension            =  $file->getClientOriginalExtension();

            if($extension == 'srt'){
                $originalPath   =   $file->getRealPath();
                $fileContent    = file_get_contents($originalPath);
                $srtFile        = Subtitles::load($fileContent, 'srt');
                file_put_contents($originalPath,$srtFile->content('vtt'));
            }

            $fileName             = 'vtt'.DIRECTORY_SEPARATOR.$id.DIRECTORY_SEPARATOR.$key.'-'.rand().'.vtt';
            $obj[$i]['language']  = $key;
            $obj[$i]['url']       = $fileName;
            $obj[$i]['label']     = ucfirst($key);
            $obj[$i]['kind']      = 'subtitles';
            $obj[$i]['default']   = false;

            $i++;
            $result = $s3Client->putObject ( array ('Bucket' => $awsS3Bucket,'Key' => $fileName,'folder'=>'vtt','SourceFile' => $file,'ACL' => 'public-read', 'ContentType' => 'text/vtt' ) );
          }
          $videoObj         = new video();
          $videoObj         = $videoObj->findorfail($id);
          $existingSubtitle = json_decode($videoObj->subtitle,true);

          if(is_array($existingSubtitle) && !empty($existingSubtitle)){
            for($i=0; $i < count($existingSubtitle); $i++){
              $obj[count($obj)] = $existingSubtitle[$i];
            }
            $videoObj->subtitle = json_encode($obj);

          }else{
              $videoObj->subtitle = json_encode($obj);
          }
          $videoObj->save();
        }
      }
      catch (\Exception $e) {
          \Log::info($e->getMessage());
      }
      return $this->getSuccessJsonResponse(['response' => $obj]);
    }

    public function generateSprite() {

      $inputArray = $this->request->all();
      $result     = [];
      if(!empty($inputArray['video_id'])) {
        $class        = new GenerateSpriteImage();
        $videoDetail  = Video::selectRaw('id, aws_prefix')->find($inputArray['video_id']);

        $presets = VideoPreset::where('format','ts')->orderBy('is_active',1)->orderBy('id', 'desc')->first();
        if(!empty($presets)) {
          $videoInfo['width'] = 192;
          $videoInfo['height'] = 108;
          $videoInfo['prefix'] = $videoDetail->aws_prefix;
          $videoInfo['video_id'] = $videoDetail->id;
          $videoInfo['preset_id'] = $presets->aws_id;
          try {
            $result = $class->create_sprite($videoInfo);
          }
          catch (\Exception $e) {
            \Log::info($e->getMessage());
          }
        }
      }
        return $this->getSuccessJsonResponse(['response' => $result]);
    }

    public function getHeaderVideoProgress(){          
      $videoInfo  = $detailVideo = $result= [];
      $videoInfo = Video::selectRaw('id,title, job_status, upload_percentage')->where('job_status', 'Progressing')->where('is_archived','0')->get()->toArray();
      $result['video_info'] = $videoInfo;
      $videoIds=$this->request->video_ids;
      $detailVideo = Video::selectRaw('id,title, job_status, upload_percentage')->where('is_archived', '0')->whereIn('id', $videoIds)->get()->toArray();
      $result['video_detail']= $detailVideo;
      return $this->getSuccessJsonResponse(['response' => $result]);
    }

    /**
     * Method to handle the audio upload of a video
     * 
     * @return Json
     */
    public function postUploadTrailer() {
      $finalResponse = '';
      $files = $this->request->file();
      // Assign video id
      $id    = $this->request->input('id');
      $trailerName = $this->request->trailer_name;
      $s3Client = $this->awsRepository->awsS3Client;
      $awsS3Bucket = env('AWS_BUCKET');
      try {
        if(!empty($files)) {
          foreach($files as $key =>$file){
            if($file->getError() > 0){
              $exceptionMSG = $this->uploadException($file->getError()); 
              return $this->getErrorJsonResponse([],$exceptionMSG);
            } else {
              $extension =  $file->getClientOriginalExtension();
              /**
               * @todo
               * The mime type of the file has to be checked instead of extension
               */
              $s3srcPath = config("contus.base.media.video_trailer.s3_location_trailer_source");
              $trailerFileName = $s3srcPath.$id.'-'.$key.'-'.rand().'.'.$extension;
              $result = $s3Client->putObject ( array ('Bucket' => $awsS3Bucket,'Key' => $trailerFileName,'folder'=>'vtt','SourceFile' => $file,'ACL' => 'public-read' ) );
            }
          }
          $response['trailer_url'] = $result ['ObjectURL'];
        }
      }
      catch (\Exception $e) {
        \Log::info($e->getMessage());
      }
      return $this->getSuccessJsonResponse(['response' => $response], 'The file uploaded with success');
    }

    /**
     * Method to handle the audio upload of a video
     * 
     * @return Json
     */
    public function postUploadAudio() {
      $finalResponse = '';
      $this->request->validate([
        'id' => 'required',
    ]);
      $files = $this->request->file();
      // Assign video id
      $id    = $this->request->input('id');
      $audioName = $this->request->audio_name;
      /*
        @todo To validate for unique audio track names
       $this->request->validate([
        'audio_name' => 'required|unique:video_audio_uploads,audio_title',
      ]);
       */
      $s3Client = $this->awsRepository->awsS3Client;
      $awsS3Bucket = env('AWS_BUCKET');
      try {
        if(!empty($files)) {
          foreach($files as $key =>$file){
            if($file->getError() > 0){
              $exceptionMSG = $this->uploadException($file->getError()); 
              return $this->getErrorJsonResponse([],$exceptionMSG);
            } else {
              $extension            =  $file->getClientOriginalExtension();
              /**
               * @todo
               * The mime type of the file has to be checked instead of extension
               */
              $s3srcPath = config("contus.base.media.video_lingual_audio_tracks.s3_location_audio_source");
              $audioFileName = $s3srcPath.$id.'-'.$key.'-'.rand().'.'.$extension;
              $result = $s3Client->putObject ( array ('Bucket' => $awsS3Bucket,'Key' => $audioFileName,'folder'=>'vtt','SourceFile' => $file,'ACL' => 'public-read' ) );
            } 
          }
          $audioUploadModel = new VideoAudioUploads();
          $audioUploadModel->video_id = $id;
          $audioUploadModel->audio_title = $audioName;
          $audioUploadModel->audio_src_url = $result ['ObjectURL'];
          $audioUploadModel->job_status = 'Audio Uploaded';
          $audioUploadModel->is_active = 1;
          $audioUploadModel->creator_id = 1;
          $audioUploadModel->updator_id = 1;
          $audioUploadModel->save();
          $finalResponse = $audioUploadModel->where('video_id',$id)->get();
        }
      }
      catch (\Exception $e) {
        \Log::info($e->getMessage());
      }
      return $this->getSuccessJsonResponse(['response' => $finalResponse], 'The file uploaded with success');
    }
    /**
     * Method to delete a particular audio track
     * 
     * @retuun Json
     */
    public function deleteLingualAudioTrack(){
      $isDeleted = false;
      $audioTrackId = $this->request->id;
      $this->aws = New AWSUploadRepository(New TranscodedVideo,New VideoPreset);
      $audioUploadModel = new VideoAudioUploads();
      $audioTrackData = $audioUploadModel->findorfail($audioTrackId);
      $videoModel = new Video();
      $videoData = $videoModel->findorfail($audioTrackData->video_id);
      $videoAWSPrefix = $videoData->aws_prefix;
      if($audioTrackData){
        $audioSrcURL = $audioTrackData->audio_src_url;  
        $splitURL = explode('/',$audioSrcURL);
        $audioFilename = end($splitURL);
        $audioOutputFolder = explode('.',end($splitURL));
        $audioOutputFolder = $audioOutputFolder[0];
        $isDeleted = $this->deleteAudioTrackInVideo($videoAWSPrefix, $audioTrackData->audio_hls_prefix, $audioTrackData->audio_title, $videoData);
        if($isDeleted){
          $audioTrackSourcePath = config("contus.base.media.video_lingual_audio_tracks.s3_location_audio_source").$audioFilename;
          $audioTrackOutputPath = $videoAWSPrefix. '/'. 'audios'. '/'. $audioTrackData->audio_title . '/'. $audioOutputFolder;
          $this->aws->deleteFileFromS3Bucket($audioTrackSourcePath);
          // Initially delete all the objects inside directory
          $this->aws->deleteDIRFromS3Bucket($audioTrackOutputPath);
          // Delete the s3 directory object
          $this->aws->deleteFileFromS3Bucket($audioTrackOutputPath.'/');
          $this->aws->deleteFileFromS3Bucket($videoAWSPrefix. '/'. 'audios'. '/'. $audioTrackData->audio_title.'/');
        }
        $response = $audioTrackData->delete();
        return ($response) ? $this->getSuccessJsonResponse(['response' => $response], 'The audio track removed successfully')
                : $this->getErrorJsonResponse(['response' => $response], 'Error in removing the audio track');
      }
    }
    /**
     * Method to delete the audio track from the videos HLS file
     * 
     * @param string $videoAWSPrefix
     * @param string $audioAWSPrefix
     * @param string $audioTitle
     * @param object $videoData
     * 
     * @return boolean
     */
    public function deleteAudioTrackInVideo($videoAWSPrefix, $audioAWSPrefix, $audioTitle = null, $videoData){
      $isDeleted = $isFileMoved  = false;
      $videoAudioTrackShceduler = new VideosAudioTrackTranscodingScheduler();
      $videoCurrentFilename =  $videoAudioTrackShceduler->getFilename($videoData->hls_playlist_url, true);
      $videoHLSFile =$videoAWSPrefix . '/'. $videoCurrentFilename;
      $videoHLSContent = $this->awsRepository->fetchFileFromS3Bucket($videoHLSFile);
      $videoHLSContentstr = (string) $videoHLSContent['Body'];
      $audioTrackMainURI = $videoAWSPrefix. '/'. $audioAWSPrefix. '/'. 'playlist.m3u8';
      $formatResultBody = $this->processAudioTrackPresets($videoHLSContentstr, $audioAWSPrefix, $audioTrackMainURI, $audioTitle);
      if(!empty($formatResultBody)){
        $videoNewHLSFilename = $videoAWSPrefix . '/' .$videoAudioTrackShceduler->obtainNewFilenameVideo($videoData->id, $videoData->hls_playlist_url). '.m3u8';
        $s3Client = $this->awsRepository->awsS3Client;
        try{
          $isFileMoved = $s3Client->putObject(array(
              'Bucket' => env('AWS_BUCKET'),
              'Key' => $videoNewHLSFilename,
              'Body' => $formatResultBody,
              'ACL' => 'public-read',
              'ServerSideEncryption' => 'AES256',
          ));
          if($isFileMoved){
            $videoAudioTrackShceduler->deleteExistingVideoHLSFile($videoHLSFile, $videoNewHLSFilename, $videoData->id);
          }
          $isDeleted =  true;
        } catch (\Exception $exception){
          app('log')->error(' ###File : ' . $exception->getFile() . ' ##Line : ' . $exception->getLine() . ' #Error : ' . $exception->getMessage());
        }
      }
      return $isDeleted;
    }
    /**
     * Method to update/remove  audio tracks inside video playlist file
     * 
     * @param string $videoHLSContentstr
     * @param string $audioAWSPrefix
     * @param string $audioTrackMainURI
     * @param string $audioTitle
     * @param string type
     * 
     * @return array
     */
    public function processAudioTrackPresets($videoHLSContentstr, $audioAWSPrefix, $audioTrackMainURI, $audioTitle, $searchTxt = null){
      $formatResultBody = '';
      $audioPresetFiles = array();
      $audioMainHLSContent = $this->awsRepository->fetchFileFromS3Bucket($audioTrackMainURI);
      $audioMainHLSContentString = (string) $audioMainHLSContent['Body'];
      if($audioMainHLSContent){
        $splitAudioMainHLSContentString = explode("\n", $audioMainHLSContentString);
        foreach ($splitAudioMainHLSContentString as $string) {
          if (strpos( $string, '.m3u8') !== false) {
              $audioPresetFiles[] = $string;
          }
        }
        foreach ($audioPresetFiles as $file) {
          $audioTrackPresetURI = $audioAWSPrefix. '/'.  $file;
          if($searchTxt === null){
            $searchTxt = '#EXT-X-MEDIA:TYPE=AUDIO,GROUP-ID="stereo",LANGUAGE="ta",NAME="'.$audioTitle.'",DEFAULT=NO,AUTOSELECT=NO,URI="'.$audioTrackPresetURI.'"';
            $replaceTXT = '';
          } else {
            $insertTxt = '#EXT-X-MEDIA:TYPE=AUDIO,GROUP-ID="stereo",LANGUAGE="ta",NAME="'.$audioTitle.'",DEFAULT=NO,AUTOSELECT=NO,URI="'.$audioTrackPresetURI.'"';
            $replaceTXT = $searchTxt. "\n". $insertTxt;
          }
          $formatResultBody = str_replace($searchTxt, $replaceTXT, $videoHLSContentstr);
        }
      } else {
        $formatResultBody = false;
      }
      return $formatResultBody;
    }
    /**
     * Method to return the upload exception messages
     * 
     * @param string $uploadERRCode
     * 
     * @return string
     */
    public function uploadException($uploadERRCode){
      switch ($uploadERRCode) {
        case 1:
          $msg = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
        break;
        case 2:
          $msg = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified';
        break;
        case 3:
          $msg = 'The uploaded file was only partially uploaded';
        break;
        case 4:
          $msg = 'No file was uploaded';
        break;
        case 6:
          $msg = 'Missing a temporary folder';
        break;
        case 7:
          $msg = 'Failed to write file to disk';
        break;
        case 8:
          $msg = 'A PHP extension stopped the file upload.';
        break; 
        default: 
          $msg = "Unknown upload error"; 
        break; 
      }
      return $msg;
    }
}