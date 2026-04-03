<?php

/**
 * Banner Controller
 * To manage the functionalities related to the Banner management
 *
 * @vendor Contus
 *
 * @package cms
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */

namespace Contus\Cms\Api\Controllers\Cms;

use Contus\Base\ApiController;
use Contus\Cms\Repositories\LiveBannerRepository;
use Contus\Base\Repositories\UploadRepository;
use Contus\Base\Helpers\StringLiterals;

class LiveBannerController extends ApiController {
    /**
     * class property to hold the instance of BannerRepository
     *
     * @var \Contus\Base\Repositories\BannerRepository Construct method
     */
    public function __construct(LiveBannerRepository $bannerRepository, UploadRepository $uploadRepository) {
        parent::__construct();
        $this->repository = $bannerRepository;
        $this->uploadRepository = $uploadRepository;
    }

    /**
     * To get the Static content info.
     *
     * @return \Illuminate\Http\Response
     */
    public function getInfo() {
        // $data = $this->repository->getBanners ();

        // unset ( $data->id );
        // return ($data) ? $this->getSuccessJsonResponse ( [ 'message' => $data ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'customer::subscription.showError' ) );

        return $this->getSuccessJsonResponse(['success']);
    }

    /**
     * This function used to get the listof Banners
     *
     * @return \Contus\Base\response
     */
    public function getTestimonialList() {
        $data = $this->repository->getBannerlists();
        unset($data->id);
        return ($data) ? $this->getSuccessJsonResponse(['message' => $data]) : $this->getErrorJsonResponse([], trans('customer::subscription.showError'));
    }
    /**
     * Store a newly created Banner
     *
     * @return \Illuminate\Http\Response
     */
    public function postAdd() {
        $isCreated = false;

        if ($this->repository->addOrUpdateBannerContents()) {

            $isCreated = true;

            $this->request->session()->flash(StringLiterals::SUCCESS, trans('cms::staticcontent.banner.add.success'));
        }

        return ($isCreated) ? $this->getSuccessJsonResponse(['message' => trans('cms::staticcontent.banner.add.success')]) : $this->getErrorJsonResponse([], trans('cms::staticcontent.add.error'));
    }

    /**
     * Update the specified banners in storage.
     *
     * @param int $id 
     * @return \Illuminate\Http\Response
     */
    public function postEdit($staticId = null) {
        $isCreated = false;

        if ($this->repository->addOrUpdateBannerContents($staticId)) {
            $isCreated = true;
            $this->request->session()->flash('success', trans('cms::staticcontent.banner.update.success'));
        }

        return ($isCreated) ? $this->getSuccessJsonResponse(['message' => trans('cms::staticcontent.banner.update.success')]) : $this->getErrorJsonResponse([], trans('cms::staticcontent.update.error'));
    }
    /**
     * Method to upload banner image to local path
     * 
     * @return json
     */
    public function postBannerImage() {
        $tempImageInfo = $this->uploadRepository->setModelIdentifier('live_image')->tempPrepare()->tempUpload('live_image');
        return empty($tempImageInfo) ? $this->getErrorJsonResponse([], trans('base::general.image_message.unable_to_upload')) : $this->getSuccessJsonResponse([
            'info' => array_shift($tempImageInfo)
        ]);
    }
    public function postMobileBannerImage() {
        $tempImageInfo = $this->uploadRepository->setModelIdentifier('live_image')->tempPrepare()->tempUpload('live_image');
        return empty($tempImageInfo) ? $this->getErrorJsonResponse([], trans('base::general.image_message.unable_to_upload')) : $this->getSuccessJsonResponse([
            'info' => array_shift($tempImageInfo)
        ]);
    }
    /**
     * Controller function to delete image of a category.
     *
     * @param integer $id The id of the category.
     * @return Ambigous <\Contus\Base\response, \Illuminate\Http\JsonResponse>
     */
    public function postDeleteBannerImage($id) {
        $isImageDeleted = false;
        try {
            /**
             * Call the deleteCategoryImage repository method to delete image of a category.
             */
            if ($this->repository->deleteBannerImage($id)) {
                $isImageDeleted = true;
                $this->request->session()->flash(StringLiterals::SUCCESS, trans('base::general.image_message.image_delete_success'));
            }
        } catch (Exception $e) {
            /**
             * Handle the error exception when the category of the image does not exist.
             */
            $this->request->session()->flash(StringLiterals::ERROR, trans('base::general.image_message.record_does_not_exist.', ['moduleName' => 'Banner']));
            $isImageDeleted = true;
        }
        /**
         * If the image of the category is deleted successfully, return the success response.
         * If the image of the category is not deleted successfully, return the failure resposne.
         */
        return ($isImageDeleted) ? $this->getSuccessJsonResponse([
            StringLiterals::MESSAGE => trans('video::categories.message.image-delete-success')
        ]) : $this->getErrorJsonResponse([], trans('video::categories.message.image-delete-error'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function searchVideos() {
        $fetch['search_videos'] = $this->repository->getSearachVideo();
        if (array_filter($fetch)) {
            return $this->getSuccessJsonResponse(['message' => trans('video::videos.fetch.success'), 'response' => $fetch]);
        } else {
            return $this->getErrorJsonResponse([], trans('video::videos.fetch.error'));
        }
    }
}
