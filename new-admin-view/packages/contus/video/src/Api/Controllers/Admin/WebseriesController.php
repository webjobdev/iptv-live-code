<?php
/**
 * Webseries Controller
 *
 * To manage the video Webseries.
 *
 * @name       Webseries Controller
 * @version    1.0
 * @author     Contus Team <developers@contus.in>
 * @copyright  Copyright (C) 2019 Contus. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Api\Controllers\Admin;

use Contus\Base\ApiController;
use Contus\Base\Helpers\StringLiterals;
use Contus\Base\Repositories\UploadRepository;
use Contus\User\Models\SiteLanguage;
use Contus\Video\Models\Category;
use Contus\Video\Models\Group;
use Contus\Video\Repositories\WebseriesRepository;
use Illuminate\Http\Request;

class WebseriesController extends ApiController
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
    public function __construct(WebseriesRepository $webseriesRepository, UploadRepository $uploadRepository)
    {
        parent::__construct();
        $this->repository = $webseriesRepository;
        $this->uploadRepository = $uploadRepository;
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
        $addWebseries = $this->repository->addOrUpdateWebseries();

        if ($addWebseries === "session_expire") {

            return redirect('admin/auth/login')->with('message', trans('video::webseries.session_expire'));
        } else {
            $isWebseriesAdd = false;
            if ($addWebseries) {
                $isWebseriesAdd = true;
                $this->request->session()->flash(StringLiterals::SUCCESS, trans('video::webseries.added'));
            }
            return ($isWebseriesAdd) ? $this->getSuccessJsonResponse([
                StringLiterals::STATUS => 'success',
                StringLiterals::MESSAGE => trans('video::webseries.success.added'),
            ]) : $this->getErrorJsonResponse([
                [
                    StringLiterals::STATUS => 'error',
                    StringLiterals::MESSAGE => trans('video::webseries.error.added'),
                ],
            ]);
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
        $editWebseries = $this->repository->addOrUpdateWebseries($id);

        $isWebseriesEdit = false;
        if ($editWebseries) {
            $isWebseriesEdit = true;
            $this->request->session()->flash(StringLiterals::SUCCESS, trans('video::webseries.updated'));
        }
        return ($isWebseriesEdit) ? $this->getSuccessJsonResponse([
            StringLiterals::STATUS => 'success',
            StringLiterals::MESSAGE => trans('video::webseries.success.updated'),
        ]) : $this->getErrorJsonResponse([
            [
                StringLiterals::STATUS => 'error',
                StringLiterals::MESSAGE => trans('video::webseries.error.updated'),
            ],
        ]);
    }

/**
 * Upload the thumbnail image
 *
 * @param string $modelIdentifier
 * @return Response
 */
    public function postThumbnail()
    {
        $moduleName = 'video-image';
        $tempImageInfo = $this->uploadRepository->setModelIdentifier('webseries_thumbnails')->tempPrepare()->tempUpload($moduleName, $this->request->size);
        return empty($tempImageInfo) ? $this->getErrorJsonResponse([], trans('video::videos.messsage.resolutionInvalid')) : $this->getSuccessJsonResponse(['info' => array_shift($tempImageInfo)]);
    }

/**
 * Upload the poster images
 *
 * @param string $modelIdentifier
 * @return Response
 */
    public function postPosters()
    {
        $moduleName = 'video-image';
        $tempImageInfo = $this->uploadRepository->setModelIdentifier('webseries_posters')->tempPrepare()->tempUpload($moduleName, $this->request->size);

        return empty($tempImageInfo) ? $this->getErrorJsonResponse([], trans(StringLiterals::UNABLE_TO_UPLOAD)) : $this->getSuccessJsonResponse(['info' => array_shift($tempImageInfo)]);
    }

    public function getWebseriesEdit($id)
    {
        $getWebseries = $this->repository->getWebseries($id);
        return (is_null($getWebseries) ? $this->getErrorJsonResponse([], null, 404) : $this->getSuccessJsonResponse(['response' => $getWebseries]));
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
                'language' => SiteLanguage::where('is_active', 1)->get()->toArray(),
                'webseries_categories' => Category::where('is_web_series', 1)->where('is_active', 1)->get()->toArray(),
                'video_genres' => Group::where('is_active', 1)->get()->toArray(),
            ],
        ]);
    }
    public function postAction()
    {
        if ($this->request->has(StringLiterals::SELECTED_CHECKBOX) && is_array($this->request->get(StringLiterals::SELECTED_CHECKBOX))) {
            $isActionCompleted = $this->repository->deleteAction($this->request->input(StringLiterals::SELECTED_CHECKBOX), 'delete');
            return $isActionCompleted ? $this->getSuccessJsonResponse([], trans('video::videos.message.bulk-activate')) : $this->getErrorJsonResponse([], trans(StringLiterals::INVALID_REQUEST_TRANS), 403);
        }
    }

    /**
     * Function to bulk activate or deactivate the category in the database.
     *
     * @see \Contus\Base\ApiController::postAction()
     * @return \Illuminate\Http\Response
     */
    public function postBulkUpdateStatus()
    {
        if ($this->request->has(StringLiterals::SELECTED_CHECKBOX) && is_array($this->request->get(StringLiterals::SELECTED_CHECKBOX))) {
            if ($this->request->get('status') === 1) {
                $isActionCompleted = $this->repository->webseriesActivateOrDeactivate($this->request->input(StringLiterals::SELECTED_CHECKBOX), 'activate');
                return $isActionCompleted ? $this->getSuccessJsonResponse([], trans('video::videos.message.bulk-activate')) : $this->getErrorJsonResponse([], trans(StringLiterals::INVALID_REQUEST_TRANS), 403);
            } else if ($this->request->get('status') === 0) {
                $isActionCompleted = $this->repository->webseriesActivateOrDeactivate($this->request->input(StringLiterals::SELECTED_CHECKBOX), 'deactivate');
                return $isActionCompleted ? $this->getSuccessJsonResponse([], trans('video::videos.message.bulk-deactivate')) : $this->getErrorJsonResponse([], trans(StringLiterals::INVALID_REQUEST_TRANS), 403);
            }
        }
    }

}
