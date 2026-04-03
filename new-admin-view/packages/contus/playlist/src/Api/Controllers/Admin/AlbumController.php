<?php

/**
 * AlbumController
 *
 * To manage the audio album management such as create, edit and delete
 *
 * @name AlbumController
 * @version 1.0
 * @author Contus Team <developers@contus.in>
 * @copyright Copyright (C) 2018 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Playlist\Api\Controllers\Admin;

use Contus\Playlist\Repositories\AlbumRepository;
use Contus\Playlist\Repositories\AudioBaseRepository;
use Contus\Playlist\Repositories\AudioRepository;
use Contus\Base\ApiController;
use Contus\Base\Helpers\StringLiterals;
use Contus\Base\Repositories\UploadRepository;
use Illuminate\Http\Request;

class AlbumController extends ApiController
{
    /**
     * class property to hold the instance of UploadRepository
     *
     * @var \Contus\Base\Repositories\UploadRepository
     */
    public $uploadRepository;

    /**
     * Class construct method initialization
     */
    public function __construct(AlbumRepository $albumRepository, UploadRepository $uploadRepository)
    {
        parent::__construct();
        $this->repository = $albumRepository;
        $this->uploadRepository = $uploadRepository;
        $this->audioRepository = new AudioRepository();
        $this->audioBaseRepository = new AudioBaseRepository();
    }
    /**
     * Method to get basic informations
     *
     * @vendor Contus
     * @return Illuminate\Http\Response
     */
    public function getInfo()
    {
        return $this->getSuccessJsonResponse([
            'info' => [
                'artists' => $this->audioBaseRepository->getAllAudioArtists(),
                'audio_language' => $this->audioBaseRepository->getAllAudioLanguageCategory(),
                'audio_genres' => $this->audioBaseRepository->getAllAudioGenres(),
                'album_rules' => $this->repository->getRules(),
                'audio_rules' => $this->audioRepository->getRules(),
                'rules' => array_merge($this->repository->getRules(), $this->audioRepository->getRules()),
            ],
        ]);

    }
    public function postAdd()
    {
        $addAlbum = $this->repository->addOrUpdateAlbum();
        if ($addAlbum == "session_expire") {
            return redirect('admin/auth/login')->with('message', trans('audio::album.session_expire'));
        } else {
            $isAlbumAdd = false;
            if ($addAlbum) {
                $isAlbumAdd = true;
                $this->request->session()->flash('success', trans('audio::album.added'));
            }
            return ($isAlbumAdd) ? $this->getSuccessJsonResponse(['albumId' => $addAlbum[0], 'albumThumbnail' => $addAlbum[1]], trans('audio::album.success.added'))
            : $this->getErrorJsonResponse(['status' => 'error', 'message' => trans('audio::album.error.added')]);
        }
    }
    /**
     * Add the specified resource in storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function postEdit($id)
    {
        $editAlbum = $this->repository->addOrUpdateAlbum($id);
        $isAlbumEdit = false;
        if ($editAlbum) {
            $isAlbumEdit = true;
            $this->request->session()->flash(StringLiterals::SUCCESS, trans('audio::album.success.updated'));
        }
        return ($isAlbumEdit) ? $this->getSuccessJsonResponse([
            StringLiterals::STATUS => 'success', StringLiterals::MESSAGE => trans('audio::album.success.updated'),
        ]) : $this->getSuccessJsonResponse([StingLiterals::STATUS => 'error', StringLiterals::MESSAGE => trans('audio::album.error.updated')]);
    }

    public function getAlbumToEdit($id)
    {
        $getAlbum = $this->repository->getAlbum($id);
        return (is_null($getAlbum) ? $this->getErrorJsonResponse([], null, 404) : $this->getSuccessJsonResponse(['response' => $getAlbum]));
    }
    /**
     * Method to update the audio data as bulk
     *
     * @vendor contus
     * @return \Illuminate\Http\Response
     */
    public function postAudioBulkUpdate(){
        $audioUpdate = $this->repository->audioBulkupdate();
        if($audioUpdate){
            $formType = ($this->request->has('formType')) ? $this->request->formType : '';
            switch($formType){
                case 'audio-add':
                    $this->request->session()->flash('success', trans('audio::album.message.audio_add_success'));
                break;
                case 'album-add':
                    $this->request->session()->flash('success', trans('audio::album.added'));
                break;
                case 'album-edit':
                    $this->request->session()->flash('success', trans('audio::album.success.updated'));
                break;
                default:
                    $this->request->session()->flash('success', trans('audio::album.message.audio_bulk_update_success'));
                break;
            }
        }
        return ($audioUpdate) ? $this->getSuccessJsonResponse([], trans('audio::album.message.audio_bulk_update_success'))
        : $this->getErrorJsonResponse([], trans('audio::album.message.audio_bulk_update_error'));
    }
    /**
     * Controller function to get the album related audios.
     *
     * @param integer $id The id of the album.
     * @return Ambigous <\Contus\Base\response, \Illuminate\Http\JsonResponse>
     */
    public function getAudioAlbums($id)
    {
        $getAudioAlbums = $this->repository->getAudioAlbums($id);
        return (is_null($getAudioAlbums)) ? $this->getErrorJsonResponse([], null, 404) : $this->getSuccessJsonResponse([
            'AudioAlbums' => $getAudioAlbums,
        ]);
    }
}
