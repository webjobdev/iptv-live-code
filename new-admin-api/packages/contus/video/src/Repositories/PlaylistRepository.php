<?php

/**
 * Playlist Repository
 *
 * To manage the functionalities related to the Playlist module from Playlist Controller
 *
 * @name PlaylistRepository
 * @vendor Contus
 * @package Audio
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2019 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Repositories;

use Contus\Video\Models\Playlists;
use Contus\Video\Repositories\AWSUploadRepository;
use Contus\Base\Repositories\UploadRepository;
use Contus\Base\Repository as BaseRepository;
use Contus\Video\Models\Video;
use Contus\Base\Helpers\StringLiterals;
use Contus\Video\Models\Playlist;
use Contus\Video\Models\PlaylistTranslation;
use Contus\Video\Models\VideoAdminPlaylist;
use Contus\Video\Models\TranscodedVideo;
use Contus\Video\Models\VideoPreset;
use Contus\Video\Models\PlaylistCategory;
use Contus\Video\Models\Category;
use Contus\Video\Models\CollectionPlaylist;
use Contus\Video\Models\AdminPlaylistTranslation;




class PlaylistRepository extends BaseRepository
{

    /**
     * Class property to hold the key which hold the user object
     *
     * @var object
     */
    protected $_playlist;
    /**
     * Class property to hold the key which hold the group name requested
     *
     * @var string
     */
    protected $requestedPlaylists = 'q';
    /**
     * Construct method
     *
     * @vendor Contus
     *
     * @package Audio
     * @param Contus\Video\Models\Playlist $palylist
     */
    public function __construct(Playlists $playlists, UploadRepository $uploadRepository)
    {
        parent::__construct();
        $this->_playlist = $playlists;
        $this->videos = new Video();
        $this->uploadRepository = $uploadRepository;
        $this->awsRepository = new AWSUploadRepository(new TranscodedVideo(), new VideoPreset());
        $this->setRules([
            'name' => 'required|unique:playlists,name',
            // 'order' => 'required|numeric|unique:playlists,playlist_order',
            'video' => 'required'
        ]);
        $this->setMessage('name.unique', 'Name already exists');
    }
    /**
     * Prepare the grid
     * set the grid model and relation model to be loaded
     *
     * @vendor Contus
     * @package Audio
     * @return Contus\Video\Repositories\BaseRepository
     */
    public function prepareGrid(){
        $this->setGridModel($this->_playlist)->setEagerLoadingModels(['playlistVideos','playlistVideosOrder','playlist_translation']);
        return $this;
    }
    /**
     * Grid heading method
     * 
     * @vendor Contus
     * @package Audio
     * @return array
     */
    public function getGridHeadings(){
        return ['heading' => [
            ['name' => 'Name', 'value' => 'name', 'sort' => true],
            ['name' => 'Order', 'value' => 'playlist_order', 'sort' => true],
            ['name' => 'No of videos', 'value' => 'is_active', 'sort' => false],
            ['name' => 'Status', 'value' => 'is_active', 'sort' => false],
            ['name' => 'Added On', 'value' => '', 'sort' => false],
            ['name' => 'Action', 'value' => '', 'sort' => false]
        ]];
    }
    /**
     * Grid heading method
     * 
     * @vendor Contus
     * @package video
     * @return array
     */
    public function getGridHeadingsVideo(){
        return ['heading' => [
            ['name' => 'Video name', 'value' => 'video_title', 'sort' => false, 'class' => 'false'],
                // ['name' => trans('video::playlist.views'), 'value' => 'artist_name', 'sort' => false, 'class' => 'false'],
                // ['name' => trans('video::playlist.album'), 'value' => 'album_name', 'sort' => false, 'class' => 'false'],
                // ['name' => trans('video::general.action'), 'value' => '', 'sort' => false]
        ]];
    }

    public function postviewDeletePlaylistSong($id){
        
        if($this->request->video_id){
            $this->_playlist = $this->_playlist->find($id)->playlistVideos()->detach($this->request->video_id);
            }else{
             $this->_playlist=true;   
            }
            return $this->_playlist;
      }
    /**
     * Store a newly created data.
     *
     * @param int $id
     *
     * @vendor Contus
     * 
     * @package audio
     * @return boolean
     */
    public function addOrUpdatePlaylist($id = null) {
        if (!empty($id)) {
            $playlist = $this->_playlist->find($id);
            $this->setRule('name', 'required|unique:playlists,name,'.$id);
            // $this->setRule('description', 'required');
            // $this->setRule('category', 'required');

            // $this->setRule('order', 'required|numeric|unique:playlists,playlist_order,'.$id);
        } else {
            $playlist = $this->_playlist;
            $playlist->creator_id = \Auth::user()->id;
        }
        $this->_validate();
        $playlist->updator_id = \Auth::user()->id;
        $playlist->fill($this->request->except('_token'));
        $playlist->playlist_order = $this->request->order?$this->request->order:0;
        /** call to method to move image to s3 Bucket */
        if ($this->request->image && $this->request->is_image_updated == 1) {
            $this->deletePlaylistImage($id);
            $fileName = $playlist->getImageBaseName($this->request->image);
            $folderName = config("contus.base.image.audio_playlist_image.s3_location");
            $localStoragePath = public_path() . DIRECTORY_SEPARATOR . config("contus.base.image.audio_playlist_image.temporary_image_storage_path");
            $s3BucketImgFilename = $this->uploadTos3Bucket($fileName, $folderName, $localStoragePath);
            $s3BucketImgURL = $folderName . $s3BucketImgFilename;
            $playlist->playlist_image = $s3BucketImgURL;
        }
        if ($playlist->save() && $this->request->video) {
            /** call to method to save audios to playlist */

            $lastInsertedID = $playlist->id;
            $this->addAudioTracksToPlaylist($this->request->video, $lastInsertedID);

            // $this->savePlaylistCategories($playlist['id']);
            return $playlist;
            // $playListTranslation = PlaylistTranslation::where('playlist_id',$playlist->id)->first();
    
            // if($playListTranslation==null){
            // $playListTranslation = new PlaylistTranslation();
            // $playListTranslation->playlist_id = $playlist->id;
            // }
            // $playListTranslation->name = $this->request->name_hindi;
            // $playListTranslation->language_id = 2;
            // $playListTranslation->save();
            // return true;
        }

         
    }

    /**
     * Function to save categories of a video in the database.
     *
     * @param integer $id
     * The id of the video whose categories are being saved.
     */
    public function savePlaylistCategories($id)
    {
        $this->playlistCategory = new PlaylistCategory ();
        $this->collectionPlaylist = new CollectionPlaylist ();

        $this->playlistCategory->where('playlist_id', $id)->delete();
        $this->collectionPlaylist->where('playlist_id', $id)->delete();

        if($this->request->has('category') && !empty($this->request->category)) {
            $categoryId = $this->request->category;
            $this->playlistCategory = new PlaylistCategory ();
            $this->playlistCategory->playlist_id = $id;
            $this->playlistCategory->category_id = $categoryId;
            $this->playlistCategory->save();
        }

        if($this->request->has('group') && !empty($this->request->group)) {
            $group = $this->request->group;
            $category = $this->request->category;
            $category = Category::find($category);
            $this->collectionPlaylist = new CollectionPlaylist ();
            $this->collectionPlaylist->playlist_id = $id;
            $this->collectionPlaylist->group_id = $group;
            $this->collectionPlaylist->parent_cateogry_id = $category->parent_id;
            $this->collectionPlaylist->save();
        }

    }

    /**
     * Function to apply filter for search of videos grid
     *
     * @param mixed $builderVideos
     * @return \Illuminate\Database\Eloquent\Builder $builderVideos The builder object of videos grid.
     */
    protected function searchFilter($builderVideos)
    {    
      
        $searchRecord = $this->request->has(StringLiterals::SEARCHRECORD) && is_array($this->request->input(StringLiterals::SEARCHRECORD)) ? $this->request->input(StringLiterals::SEARCHRECORD) : [];
        $title = $is_active = $type  = $category = $name = $id =  null;
        extract($searchRecord);
       
        if ($id) {
            $builderVideos = $builderVideos->where('id', intval($id));
        }
        /**
         * Check if the title of the video is present in the video search.
         * If yes, then use it in filter.
         */
        if ($name) {
            $builderVideos = $builderVideos->where('name', 'like', '%' . $name . '%');
        }
        
     
           
        if (is_numeric($is_active)) {
            $builderVideos = $builderVideos->where(StringLiterals::ISACTIVE, $is_active);
        }

      
        return $builderVideos;
    }

    public function allPlaylists(){
        $recordsPerPage = 10;
        $playlistModel = $this->_playlist;
        if($this->request->has('type') && $this->request->type == 'search'){
            $searchKey = $this->request->keyword;
            $playlistModel = $playlistModel->where('name', 'like', '%'.$searchKey.'%');
        }
        $playlists  = $playlistModel->orderBy('id','DESC')->paginate($recordsPerPage)->toArray();
        return $playlists;
    }

    /**
     * update grid records collection query
     *
     * @param mixed $builder
     * @return mixed
     */
    protected function updateGridQuery($builder)
    {
        /**
         * updated the grid query by using this function and apply the video condition.
         */
       
        return $builder->selectRaw('playlists.*,  playlists.id as formatted_created_date, playlists.id as formatted_published_date');
    }
    /**
     * Method to add audio list to the playlist
     * 
     * @vendor Contus
     * 
     * @package Audio
     * @param string $playlistAudios
     * @param int $lastInsertedID
     * @return void
     */
    public function addAudioTracksToPlaylist( $playlistAudios, $lastInsertedID ){
        $flag = false;
        $playlist_videos;
        if(isset($this->request->video_order)){
            $arr = [];
            $i=0;
            foreach($this->request->video_order as $k => $v){
                $arr[$i]['video_id'] = $v['id'];
                $arr[$i]['video_order'] = $v['video_order']?$v['video_order']:0;
                $i++;
            }
            $playlist_videos = $arr;
        } else {
            $playlist_videos = $playlistAudios;
        }
        $playlistAudios = (!empty($playlistAudios) && !is_array($playlistAudios)) ? explode(',', $playlistAudios): $playlistAudios;
        if(is_array( $playlistAudios)){
            $playlistBuilder = $this->_playlist->find( $lastInsertedID );
            $playlistBuilder->playlistVideos()->sync($playlist_videos);
            return $flag = true;
        }
    }
    /**
     * Repository function to delete thumbnail.
     *
     * @vendor Contus
     * 
     * @package Audio
     * @param integer $id
     * @return boolean True if the thumbnail is deleted and false if not.
     */
    public function deletePlaylistImage($id){
        /**
         * Check if id exists.
         */
        if (!empty($id)) {
            $playlist = $this->_playlist->findorfail($id);
            $playlistImage = $playlist->playlist_image;
            if (!empty($playlistImage)) {
                $URL = $playlist->getImageBaseName($playlist->playlist_image);
                /** call to method to delete image in S3 bucket */
                $imageURL = config("contus.base.image.audio_playlist_image.s3_location") . $URL;

                $this->uploadRepository->deleteFileFromS3Bucket($imageURL);
                /** Process to delete image from local storage path */
                $localFilePath = public_path() . DIRECTORY_SEPARATOR . config("contus.base.image.audio_playlist_image.temporary_image_storage_path");
                $this->uploadRepository->deleteImageFileInLocalPath($localFilePath, $URL);
                /**
                 * Empty the image_url and image_path field in the database.
                 */
                $playlist->playlist_image = '';
                $playlist->save();
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Repository function to get the artist related audio list
     *
     * @param integer $id
     * @return variable
     */
    public function getVideoPlaylists($id)
    {
        $this->_playlist = $this->_playlist->find($id);
        if (is_null($this->_playlist)) {
            return $this->_playlist;
        }
        $video = $this->_playlist->playlistVideos()->groupBy('videos.id')->where('is_archived', 0)->paginate($this->request->rowsPerPage)->toArray();
        return ['playlist' => $this->_playlist, 'videos' => $video,'gridHeadings'=>$this->getGridHeadingsVideo()];
    }

    public function playlistVideos()
    {
        $arrayvideos=[];
         $finalPlaylistVideos = array();
         $videos = $this->videos->select('videos.id', 'videos.title')->whereIn('videos.id',$this->request->video_ids)->get();
         if (count($videos)>0) {
        foreach ($this->request->video_ids as $ids) {
            $video = $this->videos->select('videos.id', 'videos.title')->where('videos.id',$ids)->get();
              if(count($video)>0){
                $i =0;
                foreach ($video as $key=>$playlistVideo) {
                    if($this->request->playlist_id){
                        
                        $playlistsVideoss = VideoAdminPlaylist::where('playlist_id',$this->request->playlist_id)->where('video_id',$playlistVideo->id)->first();
                        if($playlistsVideoss){
                            $video[$i]['video_order'] = $playlistsVideoss->video_order; 
                        } else {
                            $video[$i]['video_order'] = null; 
                        }
                    }else {
                            $video[$i]['video_order'] = null; 
                    }$i++;
                }
            }
            $arrayvideos[]= $video->toArray();
        }
        foreach ($arrayvideos as $arrayv){
           $finalPlaylistVideos = array_merge($finalPlaylistVideos, array_values($arrayv)) ;
        }
        return $finalPlaylistVideos;
    }else {
         return null;
    }
    /*$videos = $this->videos->select('videos.id', 'videos.title')->whereIn('videos.id',$this->request->video_ids)->get();
        if (count($videos)>0) {
            $i =0;
            foreach($videos as $k => $v){
                if($this->request->playlist_id){
                    $playlistsVideos = VideoAdminPlaylist::where('playlist_id',$this->request->playlist_id)->where('video_id',$v->id)->first();
                    if($playlistsVideos){
                        $videos[$i]['video_order'] = $playlistsVideos->video_order; 
                    } else {
                        $videos[$i]['video_order'] = null; 
                    }
                } else {
                    $videos[$i]['video_order'] = null; 
                }
                $i++;
            }
            return $videos;

        } else {
            return null;
        }*/
    }
     /**
     * Repository function to get the artist related audio list
     *
     * @param integer $id
     * @return variable
     */
    public function postDeletePlaylistSong($id)
    {
        if($this->request->audio_id){
        $this->_playlist = $this->_playlist->find($id)->playlistVideos()->detach($this->request->audio_id);
        }else{
         $this->_playlist=true;   
        }
        return $this->_playlist;
    }
    /**
     * Method to return audio tracks suggestions based on the search term
     * 
     * @vendor Contus
     * 
     * @package Audio
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function searchAudioTracks(){
        $this->setRules(['search' => 'required', 'order'=>'sometimes|in:title', 'sort'=>'sometimes|in:asc,desc']);
        $this->validate($this->request, $this->getRules());
        $searchKey = $this->request->search;
        $audio =  $this->videos->active()->where ( 'job_status', 'Complete' )->where(function($query) use ($searchKey) {
            $query->orwhere('slug', 'like', '%'.$searchKey.'%')->orwhere('audio_title', 'like', '%'.$searchKey.'%');
        });
        $fields = 'audios.id, audios.audio_title, audios.slug';
        $audio->selectRaw($fields)->groupBy('audios.id');
        $audio->orderBy('id', 'desc');
        return $audio->paginate(config('access.perpage'));
    }
    /**
     * Method to add audio list to the playlist
     * 
     * @vendor Contus
     * 
     * @package Audio
     * @param string $playlistVideos
     * @param int $lastInsertedID
     * @return void
     */
    public function addVideoToPlaylist( $playlistVideos, $lastInsertedID ){
        $flag = false;
        $playlistVideos = (!empty($playlistVideos) && !is_array($playlistVideos)) ? explode(',', $playlistVideos): $playlistVideos;
        if(is_array( $playlistVideos)){
            $playlistBuilder = $this->_playlist->find( $lastInsertedID );
            $count = $playlistBuilder->playlistVideos()->count();
            if($count < 100){
                $playlistBuilder->playlistVideos()->attach( $playlistVideos);
                $flag =  true;
            } else {
                $flag =  'limit reached';
            }
        } 
        return $flag;
    }
    public function fetchVideos() {
        $this->setRules(['search' => 'required']);
        $this->validate($this->request, $this->getRules());
        $searchKey = $this->request->search;
       
        $video = $this->videos->where('is_live',0)->where ( 'videos.is_active', '1' )->where ( 'job_status', 'Complete' )->where ( 'is_archived', 0 )->where(function($query) use ($searchKey) {
            $query->orwhere('slug', 'like', '%'.$searchKey.'%')->orwhere('title', 'like', '%'.$searchKey.'%');
        });
        $fields = 'videos.id, videos.title, videos.slug';
        $video->selectRaw($fields)->groupBy('videos.id');
        return $video->paginate(config('access.perpage'));
    }

    public function updateSeasonTranslation ($id) {
        
        if(!empty($id)) {
            // $this->setRules(['name'=>StringLiterals::REQUIRED]);
            // $this->validate($this->request, $this->getRules());
            $season_translation;
            if(AdminPlaylistTranslation::where('playlist_id','=', $id)->where('language_id','=',$this->request->languageCode)->count() > 0) {
                $season_translation = AdminPlaylistTranslation::where('playlist_id', '=', $id)->where('language_id', '=', $this->request->languageCode)->first();
            } else {
                $season_translation = new AdminPlaylistTranslation();
                $season_translation->playlist_id = $id;
                $season_translation->language_id = $this->request->languageCode;
            }
            $season_translation->name = $this->request->name;
            if($season_translation->save()) {
                $isUpdated = true;
            } else {
                return false;
            }
        }else {
            return false;
        }

    }
}
