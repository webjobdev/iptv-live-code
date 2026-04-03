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
namespace Contus\Audio\Repositories;

use Contus\Audio\Models\Playlists;
use Contus\Audio\Repositories\AWSUploadRepository;
use Contus\Base\Repositories\UploadRepository;
use Contus\Base\Repository as BaseRepository;
use Contus\Audio\Models\Audios;

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
     * @param Contus\Audio\Models\Playlist $palylist
     */
    public function __construct(Playlists $playlists, UploadRepository $uploadRepository)
    {
        parent::__construct();
        $this->_playlist = $playlists;
        $this->audios = new Audios();
        $this->uploadRepository = $uploadRepository;
        $this->awsRepository = new AWSUploadRepository();
        $this->setRules(['playlist_name' => 'required|unique:audio_admin_playlists,playlist_name']);
    }
    /**
     * Prepare the grid
     * set the grid model and relation model to be loaded
     *
     * @vendor Contus
     * @package Audio
     * @return Contus\Audio\Repositories\BaseRepository
     */
    public function prepareGrid(){
        $this->setGridModel($this->_playlist)->setEagerLoadingModels(['playlistAudios']);
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
            ['name' => trans('audio::playlists.playlist_name'), 'value' => 'playlist_name', 'sort' => true],
            ['name' => trans('audio::general.no_of_audio'), 'value' => 'is_active', 'sort' => false],
            ['name' => trans('audio::general.status'), 'value' => 'is_active', 'sort' => false],
            ['name' => trans('audio::general.added_on'), 'value' => '', 'sort' => false],
            ['name' => trans('audio::general.action'), 'value' => '', 'sort' => false],
        ]];
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
        if (empty($this->authUser->id)) {
            return "session_expire";
        }
        if (!empty($id)) {
            $playlist = $this->_playlist->find($id);
            $this->setRule('playlist_name', 'required|unique:audio_admin_playlists,playlist_name,'.$id);
        } else {
                $playlist = $this->_playlist;
                $playlist->creator_id = $this->authUser->id;
        }
        $this->_validate();
        $playlist->updator_id = $this->authUser->id;
        $playlist->fill($this->request->except('_token'));
        /** call to method to move image to s3 Bucket */
        if ($this->request->image && $this->request->is_image_updated == 1) {
            $this->deletePlaylistImage($id);
            $fileName = $playlist->getImageBaseName($this->request->image);
            $folderName = config("contus.base.image.audio_playlist_image.s3_location");
            $localStoragePath = public_path() . DIRECTORY_SEPARATOR . config("contus.base.image.audio_playlist_image.temporary_image_storage_path");
            $s3BucketImgFilename = $this->uploadTos3Bucket($fileName, $folderName, $localStoragePath);
            $s3BucketImgURL = $folderName . $s3BucketImgFilename;
            $playlist->playlist_thumbnail = $s3BucketImgURL;
        }
        if ($playlist->save()) {
            /** call to method to save audios to playlist */
            $lastInsertedID = $playlist->id;
            $this->addAudioTracksToPlaylist($this->request->playlist_audios, $lastInsertedID);
            return true;
        }
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
        $playlistAudios = (!empty($playlistAudios)) ? explode(',',$playlistAudios) : '';
        if(is_array( $playlistAudios)){
            $playlistBuilder = $this->_playlist->find( $lastInsertedID );
            $playlistBuilder->playlistAudios()->sync( $playlistAudios );
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
            $playlistImage = $playlist->playlist_thumbnail;
            if (!empty($playlistImage)) {
                $URL = $playlist->getImageBaseName($playlist->playlist_thumbnail);
                /** call to method to delete image in S3 bucket */
                $imageURL = config("contus.base.image.audio_playlist_image.s3_location") . $URL;

                $this->uploadRepository->deleteFileFromS3Bucket($imageURL);
                /** Process to delete image from local storage path */
                $localFilePath = public_path() . DIRECTORY_SEPARATOR . config("contus.base.image.audio_playlist_image.temporary_image_storage_path");
                $this->uploadRepository->deleteImageFileInLocalPath($localFilePath, $URL);
                /**
                 * Empty the image_url and image_path field in the database.
                 */
                $playlist->playlist_thumbnail = '';
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
        $audio =  $this->audios->active()->where ( 'job_status', 'Complete' )->where(function($query) use ($searchKey) {
            $query->orwhere('slug', 'like', '%'.$searchKey.'%')->orwhere('audio_title', 'like', '%'.$searchKey.'%');
        });
        $fields = 'audios.id, audios.audio_title, audios.slug';
        $audio->selectRaw($fields)->groupBy('audios.id');
        $audio->orderBy('id', 'desc');
        return $audio->paginate(config('access.perpage'));
    }
}
