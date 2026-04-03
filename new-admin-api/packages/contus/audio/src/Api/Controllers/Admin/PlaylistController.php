<?php

/**
 * Playlist Controller
 *
 * To manage the Audio Playlist.
 *
 * @name       Playlist Controller
 * @version    1.0
 * @author     Contus Team <developers@contus.in>
 * @copyright  Copyright (C) 2019 Contus. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Audio\Api\Controllers\Admin;

use Contus\Audio\Repositories\PlaylistRepository;
use Contus\Base\ApiController;
use Contus\Base\Helpers\StringLiterals;
use Contus\Base\Repositories\UploadRepository;
use Illuminate\Http\Request;

class PlaylistController extends ApiController
{
    /**
     * Construct method
     */
    public function __construct(PlaylistRepository $playlistRepository, UploadRepository $uploadRepository){
        parent::__construct();
        $this->repository = $playlistRepository;
        $this->uploadRepository = $uploadRepository;
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
            ],
        ]);
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
                $this->request->session()->flash(StringLiterals::SUCCESS, trans('audio::playlists.success.added'));
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

}
