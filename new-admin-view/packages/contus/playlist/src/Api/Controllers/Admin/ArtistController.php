<?php

/**
 * Artist Controller
 *
 * To manage the Audio Artist.
 *
 * @name       Artist Controller
 * @version    1.0
 * @author     Contus Team <developers@contus.in>
 * @copyright  Copyright (C) 2016 Contus. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\PLaylist\Api\Controllers\Admin;

use Illuminate\Http\Request;
use Contus\Playlist\Repositories\ArtistRepository;
use Contus\Base\ApiController;
use Contus\Base\Helpers\StringLiterals;
use Contus\Base\Repositories\UploadRepository;

class ArtistController extends ApiController
{
    /**
     * class property to hold the instance of UploadRepository
     *
     * @var \Contus\Base\Repositories\UploadRepository
     */
    public $uploadRepository;
    /**
     * Construct method
     */

    public function __construct(ArtistRepository $artistRepository, UploadRepository $uploadRepository)
    {
        parent::__construct();
        $this->repository = $artistRepository;
        $this->uploadRepository = $uploadRepository;
    }
    /**
     * get Information for create form
     * return various information request by the form
     * request will be having query param which refer to artist
     *
     * @return \Illuminate\Http\Response
     */
    public function getAdd()
    {
        return $this->getSuccessJsonResponse([
            StringLiterals::RULES => $this->repository->getRules(),
        ]);
    }

    /**
     * Add the specified resource in storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */

    public function postAdd()
    {
        $addArtist = $this->repository->addOrUpdateArtist();
        if ($addArtist) {
            $isArtistAdd = false;
            if ($addArtist) {
                $isArtistAdd = true;
                $this->request->session()->flash(StringLiterals::SUCCESS, trans('audio::artists.added'));
            }
            return ($isArtistAdd) ? $this->getSuccessJsonResponse([
                StringLiterals::STATUS => 'success',
                StringLiterals::MESSAGE => trans('audio::artists.success.added')
            ]) : $this->getErrorJsonResponse([
                [
                    StringLiterals::STATUS => 'error',
                    StringLiterals::MESSAGE => trans('audio::artists.error.added')
                ]
            ]);
        } else if ($addArtist == "session_expire") {
            return redirect('admin/auth/login')->with('message', trans('audio::artists.session_expire'));
        } else {
            return $this->getSuccessJsonResponse([StingLiterals::STATUS => 'error', StringLiterals::MESSAGE => trans('audio::artists.error.updated')]);
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
        $editArtist = $this->repository->addOrUpdateArtist($id);
        $isArtistEdit = false;
        if ($editArtist) {
            $isArtistEdit = true;
            $this->request->session()->flash(StringLiterals::SUCCESS, trans('audio::artists.updated'));
        }
        return ($isArtistEdit) ? $this->getSuccessJsonResponse([
            StringLiterals::STATUS => 'success', StringLiterals::MESSAGE => trans('audio::artists.success.updated')
        ]) : $this->getSuccessJsonResponse([StingLiterals::STATUS => 'error', StringLiterals::MESSAGE => trans('audio::artists.error.updated')]);
    }



    /**
     * Upload the image for a artist.
     *
     * @param string $modelIdentifier
     * @return Response
     */

    public function postArtistImage(){
        $tempImageInfo = $this->uploadRepository->setModelIdentifier('artist_image')->tempPrepare()->tempUpload('artist_image');
        return empty($tempImageInfo) ? $this->getErrorJsonResponse([], trans('audio::artists.messsage.unable_to_upload')) : $this->getSuccessJsonResponse([
            'info' => array_shift($tempImageInfo)
        ]);
    }
    /**
     * Controller function to delete image of a artist.
     *
     * @param integer $id The id of the artist.
     * @return Ambigous <\Contus\Base\response, \Illuminate\Http\JsonResponse>
     */
    public function postDeleteArtistImage($id)
    {
        $isImageDeleted = false;

        try {
            /**
             * Call the deleteArtistImage repository method to delete image of a artist.
             */
            if ($this->repository->deleteArtistImage($id)) {
                $isImageDeleted = true;
                $this->request->session()->flash(StringLiterals::SUCCESS, trans('audio::artists.message.image-delete-success'));
            }
        } catch (Exception $e) {
            /**
             * Handle the error exception when the artist of the image does not exist.
             */
            $this->request->session()->flash(StringLiterals::ERROR, trans('audio::artists.artist_not_exist'));
            $isImageDeleted = true;
        }
        /**
         * If the image of the artist is deleted successfully, return the success response.
         * If the image of the artist is not deleted successfully, return the failure resposne.
         */
        return ($isImageDeleted) ? $this->getSuccessJsonResponse([
            StringLiterals::MESSAGE => trans('audio::artists.message.image-delete-success')
        ]) : $this->getErrorJsonResponse([], trans('audio::artists.message.image-delete-error'));
    }


    /**
     * get Information for create form
     * return various information request by the form
     *
     * @return \Illuminate\Http\Response
     */
    public function getInfo()
    {

        return $this->getSuccessJsonResponse([
            'info' => [
                'rules' => $this->repository->getRules(),
                'locale' => trans('validation'),
            ]
        ]);
    }

    /**
     * Controller function to get the artist related audios.
     *
     * @param integer $id The id of the album.
     * @return Ambigous <\Contus\Base\response, \Illuminate\Http\JsonResponse>
     */
    public function getAudioArtists($id)
    {
        $getAudioArtists = $this->repository->getAudioArtists($id);
        return (is_null($getAudioArtists)) ? $this->getErrorJsonResponse([], null, 404) : $this->getSuccessJsonResponse([
            'audioArtists' => $getAudioArtists,
        ]);
    }

}
