<?php

namespace Contus\Organizations\Api\Controller;

use Contus\Base\ApiController;
use Contus\Base\Repositories\UploadRepository;
use Contus\Organizations\Repositories\AppCustomizationRepository;
use Illuminate\Support\Facades\Auth;

class AppCustomizationController extends ApiController
{
    protected $_customizationUpload;

    public function __construct(AppCustomizationRepository $appCustomizationRepository, UploadRepository $uploadRepository)
    {
        parent::__construct();
        $this->repository = $appCustomizationRepository;
        $this->_customizationUpload = $uploadRepository;
        $this->repository->setRequestType(static::REQUEST_TYPE);
    }

    public function getInfo()
    {
        $user = Auth::user();
        return $this->getSuccessJsonResponse(['success']);
    }

    public function postAdd()
    {
        $tvShow = $this->repository->postAdd();
        if ($tvShow == 'success') {
            return $this->getSuccessJsonResponse(['message' => trans('organizations::index.app_ctm_add.success')]);
        } else {
            return $this->getErrorJsonResponse([], $tvShow);
        }
    }

    public function postThumbnail()
    {
        $moduleName = 'tvshow-image';
        $tempImageInfo = $this->_customizationUpload
            ->setModelIdentifier(UploadRepository::MODEL_IDENTIFIER_THUMBNAIL)->tempPrepare()
            ->tempUpload($moduleName, $this->request->size);

        return empty($tempImageInfo) ? $this->getErrorJsonResponse([], trans('video::videos.messsage.resolutionInvalid')) :
            $this->getSuccessJsonResponse(['info' => array_shift($tempImageInfo)]);
    }

    public function postEdit($id)
    {
        $edit = $this->repository->postEdit($id);
        return (is_null($edit)) ?
            $this->getErrorJsonResponse([], 'Data Not Update.', 404) :
            $this->getSuccessJsonResponse(['message' => 'General Data Updated.']);
    }
}
