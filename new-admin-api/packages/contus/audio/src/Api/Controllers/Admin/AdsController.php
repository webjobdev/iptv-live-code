<?php

/**
 * Ads Controller
 *
 * To manage the Audio Ads.
 *
 * @name       Ads Controller
 * @version    1.0
 * @author     Contus Team <developers@contus.in>
 * @copyright  Copyright (C) 2019 Contus. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Audio\Api\Controllers\Admin;

use Contus\Audio\Repositories\AdsRepository;
use Contus\Base\ApiController;
use Contus\Base\Helpers\StringLiterals;
use Contus\Base\Repositories\UploadRepository;
use Illuminate\Http\Request;

class AdsController extends ApiController
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

    public function __construct(AdsRepository $adsRepository, UploadRepository $uploadRepository)
    {
        parent::__construct();
        $this->repository = $adsRepository;
        $this->uploadRepository = $uploadRepository;
    }

    /**
     * Upload the image for a artist.
     *
     * @param string $modelIdentifier
     * @return Response
     */

    public function postAdImage()
    {
        $tempImageInfo = $this->uploadRepository->setModelIdentifier('ad_image')->tempPrepare()->tempUpload();

        return empty($tempImageInfo) ? $this->getErrorJsonResponse([], trans('audio::audioAds.messsage.unable_to_upload')) : $this->getSuccessJsonResponse([
            'info' => array_shift($tempImageInfo),
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
        $addAd = $this->repository->addOrUpdateAd();
        if ($addAd) {
            $isAdAdd = false;
            if ($addAd) {
                $isAdAdd = true;
                $this->request->session()->flash(StringLiterals::SUCCESS, trans('audio::audioAds.added'));
            }
            return ($isAdAdd) ? $this->getSuccessJsonResponse([
                StringLiterals::STATUS => 'success',
                StringLiterals::MESSAGE => trans('audio::audioAds.success.added'),
            ]) : $this->getErrorJsonResponse([
                [
                    StringLiterals::STATUS => 'error',
                    StringLiterals::MESSAGE => trans('audio::audioAds.error.added'),
                ],
            ]);
        } else if ($addAd == "session_expire") {
            return redirect('admin/auth/login')->with('message', trans('audio::audioAds.session_expire'));
        } else {
            return $this->getSuccessJsonResponse([StingLiterals::STATUS => 'error', StringLiterals::MESSAGE => trans('audio::audioAds.error.updated')]);
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
        $editAd = $this->repository->addOrUpdateAd($id);
        $isAdEdit = false;
        if ($editAd) {
            $isAdEdit = true;
            $this->request->session()->flash(StringLiterals::SUCCESS, trans('audio::audioAds.updated'));
        }
        return ($isAdEdit) ? $this->getSuccessJsonResponse([
            StringLiterals::STATUS => 'success', StringLiterals::MESSAGE => trans('audio::audioAds.success.updated'),
        ]) : $this->getSuccessJsonResponse([StingLiterals::STATUS => 'error', StringLiterals::MESSAGE => trans('audio::audioAds.error.updated')]);
    }

    /**
     * Controller function to delete image of a artist.
     *
     * @param integer $id The id of the artist.
     * @return Ambigous <\Contus\Base\response, \Illuminate\Http\JsonResponse>
     */
    public function postDeleteAdImage($id)
    {
        $isImageDeleted = false;
        try {
            /**
             * Call the deleteArtistImage repository method to delete image of a artist.
             */
            if ($this->repository->deleteAdImage($id)) {
                $isImageDeleted = true;
                $this->request->session()->flash(StringLiterals::SUCCESS, trans('audio::audioAds.message.image-delete-success'));
            }
        } catch (Exception $e) {
            /**
             * Handle the error exception when the artist of the image does not exist.
             */
            $this->request->session()->flash(StringLiterals::ERROR, trans('audio::audioAds.ad_not_exist'));
            $isImageDeleted = true;
        }
        /**
         * If the image of the artist is deleted successfully, return the success response.
         * If the image of the artist is not deleted successfully, return the failure resposne.
         */
        return ($isImageDeleted) ? $this->getSuccessJsonResponse([
            StringLiterals::MESSAGE => trans('audio::artists.message.image-delete-success'),
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
                StringLiterals::RULES => $this->repository->getRules(),
                'locale' => trans('validation'),
                'isActive' => [
                    'In-active',
                    'Active',
                ],
            ],
        ]);
    }
}
