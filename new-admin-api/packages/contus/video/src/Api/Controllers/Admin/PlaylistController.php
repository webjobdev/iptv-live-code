<?php

/**
 * Playlist Controller
 *
 * To manage the Video Playlist.
 *
 * @name       Playlist Controller
 * @version    1.0
 * @author     Contus Team <developers@contus.in>
 * @copyright  Copyright (C) 2019 Contus. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Api\Controllers\Admin;

use Contus\Video\Repositories\PlaylistRepository;
use Contus\Base\ApiController;
use Contus\Base\Helpers\StringLiterals;
use Contus\Base\Repositories\UploadRepository;
use Contus\Video\Repositories\CategoryRepository;
use Contus\Video\Repositories\CollectionRepository;
use Illuminate\Http\Request;


class PlaylistController extends ApiController
{
    /**
     * Construct method
     */
    public function __construct(PlaylistRepository $playlistRepository, UploadRepository $uploadRepository, CollectionRepository $collectionsRepository, CategoryRepository $categoryRepository){
        parent::__construct();
        $this->repository = $playlistRepository;
        $this->uploadRepository = $uploadRepository;
        $this->collectionsRepository = $collectionsRepository;
        $this->categoryRepository = $categoryRepository;
        
    }
    /**
     * get Information for create form
     * return various information request by the form
     * request will be having query param which refer to Playlist
     *
     * @return \Illuminate\Http\Response
     */
    public function getAdd() {
        return $this->getSuccessJsonResponse([
            StringLiterals::RULES => $this->repository->getRules(),
        ]);
    }
    /**
     * get Information for create form
     * return various information request by the form
     *
     * @return \Illuminate\Http\Response
     */
    public function getInfo(){
        return $this->getSuccessJsonResponse([
            'info' => [
                StringLiterals::RULES => $this->repository->getRules(),
                'allCollection' => $this->collectionsRepository->getAllCollection(),
                'allCategories' => $this->categoryRepository->getAllCategoryInfo(),
                    
            ],
        ]);
    }
    /**
     * get the specified resource in storage.
     *
     * @vendor Contus
     * @package Audio
     * @return \Illuminate\Http\Response
     */
    public function getAllPlaylists(){
        $getPlaylist = $this->repository->allPlaylists();
        return (!empty($getPlaylist)) ? $this->getSuccessJsonResponse(['message' => trans('audio::playlists.success.fetch_audio'), 'data' => $getPlaylist ])
                                : $this->getErrorJsonResponse([], trans('audio::playlists.error.fetch_audio'));
    }
    
    /**
     * Add the specified resource in storage.
     *
     * @vendor Contus
     * @package Audio
     * @return \Illuminate\Http\Response
     */
    public function postAdd(){
        $addPlaylist = $this->repository->addOrUpdatePlaylist();
        if ($addPlaylist) {
            $isPlaylistAdd = false;
            if ($addPlaylist) {
                $isPlaylistAdd = true;
                // $this->request->session()->flash(StringLiterals::SUCCESS, trans('audio::playlists.success.added'));
            }
            return ($isPlaylistAdd) ? $this->getSuccessJsonResponse([
                'status' => 'success',
                'message' => trans('audio::playlists.success.added')
            ]) : $this->getErrorJsonResponse([
                [
                    'status' => 'error',
                    'message' => trans('audio::playlists.error.added')
                ]
            ]);
        } else if ($addPlaylist == "session_expire") {
            return redirect('admin/auth/login')->with('message', trans('audio::general.session_expire'));
        } else {
            return $this->getSuccessJsonResponse(['status' => 'error', 'message' => trans('audio::playlists.error.updated')]);
        }
    }
    /**
     * Edit the specified resource in storage.
     *
     * @vendor Contus
     * @package Audio
     * @return \Illuminate\Http\Response
     */
    public function postEdit(){
        $addPlaylist = $this->repository->addOrUpdatePlaylist($this->request->id);
        if ($addPlaylist) {
            $isPlaylistAdd = false;
            if ($addPlaylist) {
                $isPlaylistAdd = true;
                // $this->request->session()->flash(StringLiterals::SUCCESS, trans('audio::playlists.success.added'));
            }
            return ($isPlaylistAdd) ? $this->getSuccessJsonResponse([
                'status' => 'success',
                'message' => trans('audio::playlists.success.updated')
            ]) : $this->getErrorJsonResponse([
                [
                    'status' => 'error',
                    'message' => trans('audio::playlists.error.added')
                ]
            ]);
        } else if ($addPlaylist == "session_expire") {
            return redirect('admin/auth/login')->with('message', trans('audio::general.session_expire'));
        } else {
            return $this->getSuccessJsonResponse(['status' => 'error', 'message' => trans('audio::playlists.error.updated')]);
        }
    }


    /**
     * Controller function to get the artist related audios.
     *
     * @param integer $id The id of the album.
     * @return Ambigous <\Contus\Base\response, \Illuminate\Http\JsonResponse>
     */
    public function getPlaylistVideos($id)
    {

        $getvideoPlaylists = $this->repository->getVideoPlaylists($id);
        return (is_null($getvideoPlaylists)) ? $this->getErrorJsonResponse([], null, 404) : $this->getSuccessJsonResponse([
            'playlistVideos' => $getvideoPlaylists,
        ]);
    }

    public function postviewDeleteAction(){
       
   
        $postDeletePlaylistSong=$this->repository->postviewDeletePlaylistSong($this->request->id);
        return ($postDeletePlaylistSong) ? $this->getSuccessJsonResponse([
            StringLiterals::MESSAGE => trans('audio::playlists.success.audio_del')
        ]) : $this->getErrorJsonResponse([], trans('audio::playlists.error.audio_del'));
    }

    /**
     * Controller function to delete the playlist related audios.
     *
     * @param integer $id The id of the album.
     * @return Ambigous <\Contus\Base\response, \Illuminate\Http\JsonResponse>
     */
    public function postDeleteAction()
    {

        $postDeletePlaylistSong = $this->repository->postDeletePlaylistSong($this->request->id);
        return ($postDeletePlaylistSong) ? $this->getSuccessJsonResponse([
            StringLiterals::MESSAGE => trans('audio::playlists.success.updated')
        ]) : $this->getErrorJsonResponse([], trans('audio::playlists.error.updated'));
    }
    /**
     * Method to add video to respective playist
     * 
     *  @return \Illuminate\Http\Response
     */
    public function addVideostoPlaylist(){
        $playlistVideos = $this->request->videos;
        $playlistID = $this->request->id;
        $result = $this->repository->addVideoToPlaylist($playlistVideos, $playlistID);
        if($result){
            // $this->request->session()->flash(StringLiterals::SUCCESS, trans('video::videos.playist.flash_message'));
        }
        if($result === 'limit reached'){
            return  $this->getErrorJsonResponse([
                  [
                      'status' => 'error',
                      'message' => 'Maximum Limit reached.'
                  ]
              ]);
          }
        return ($result) ? $this->getSuccessJsonResponse([
            'status' => 'success',
            'message' => trans('video::videos.message.success')
        ]) : $this->getErrorJsonResponse([
            [
                'status' => 'error',
                'message' => trans('video::videos.message.error')
            ]
        ]);
    }
    /**
     * Method to get the audio tracks based on the search term
     * 
     * @vendor Contus
     * 
     * @package Audio
     * @return \Illuminate\Http\Response
     */
    public function getAudioTracks(){
        $data = $this->repository->searchAudioTracks();
        return (!empty($data)) ? $this->getSuccessJsonResponse(['message' => trans('audio::playlists.success.fetch_audio'), 'response' => $data ])
                                : $this->getErrorJsonResponse([], trans('audio::playlists.error.fetch_audio'));
    }
    /**
     * Method to fetch videos
     *
     * @return \Illuminate\Http\Response
     */
    public function searchVideos() {
        $fetch ['search_videos'] = $this->repository->fetchVideos();
        if (array_filter($fetch)) {
            return $this->getSuccessJsonResponse(['message' => trans('video::videos.fetch.success'), 'response' => $fetch]);
        } else {
            return $this->getErrorJsonResponse([], trans('video::videos.fetch.error'));
        }
    }

    public function playlistVideos() {
        $playlistVideos = $this->repository->playlistVideos();
        return (is_null($playlistVideos)) ? $this->getErrorJsonResponse([], null, 404) : $this->getSuccessJsonResponse([
            'playlist_videos' => $playlistVideos,
        ]);
    }

    public function addLanguage($id) 
    {
       
        $isUpdated = false;
        
        try {
            $this->repository->updateSeasonTranslation($id);
            $isUpdated = true;
            return $this->getSuccessJsonResponse ( [ ],trans ( 'video::videos.message.update-success' ));
        } catch (Exception $e) {
            $isUpdated = true;
            return $this->getErrorJsonResponse ( [], trans ( 'video::videos.resource_not_exist') );
        }
    }


}
