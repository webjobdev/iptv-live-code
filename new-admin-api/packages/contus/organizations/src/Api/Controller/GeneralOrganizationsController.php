<?php

namespace Contus\Organizations\Api\Controller;

use Contus\Base\ApiController;
use Contus\Base\Repositories\UploadRepository;
use Contus\Organizations\Repositories\GeneralOrganizationSettingRepository;
use Illuminate\Http\Request;
use Contus\Base\Helpers\StringLiterals;
use Illuminate\Support\Facades\Auth;

class GeneralOrganizationsController extends ApiController
{

    protected $_orgUpload;

    public function __construct(GeneralOrganizationSettingRepository $generalOrganizationSettingRepository, UploadRepository $uploadRepository)
    {
        parent::__construct();
        $this->repository = $generalOrganizationSettingRepository;
        $this->_orgUpload = $uploadRepository;
        $this->repository->setRequestType(static::REQUEST_TYPE);
    }

    public function getInfo()
    {
        $user = Auth::user();
        return $this->getSuccessJsonResponse(['success']);
    }

    public function postAdd()
    {
        $isCreated = false;
        if ($this->repository->addgeneralorganizationsetting()) {
            $isCreated = true;
            // $this->request->session()->flash(StringLiterals::SUCCESS, trans('cms::index.add.success'));
            return ($isCreated) ? $this->getSuccessJsonResponse(['message' => trans('organizations::index.update.success')]) : $this->getErrorJsonResponse([], trans('organizations::index.update.error'));
        }
    }

    public function postAddSetting(Request $request)
    {
        $isCreated = false;
        if ($this->repository->addorganizationsetting($request)) {
            $isCreated = true;
            // $this->request->session()->flash(StringLiterals::SUCCESS, trans('cms::index.add.success'));
            return ($isCreated) ? $this->getSuccessJsonResponse(['message' => trans('organizations::index.setting_update.success')]) : $this->getErrorJsonResponse([], trans('organizations::index.setting_update.error'));
        }
    }

    public function postThumbnail()
    {
        $moduleName = 'tvshow-image';
        $tempImageInfo = $this->_orgUpload
            ->setModelIdentifier(UploadRepository::MODEL_IDENTIFIER_THUMBNAIL)->tempPrepare()
            ->tempUpload($moduleName, $this->request->size);

        return empty($tempImageInfo) ? $this->getErrorJsonResponse([], trans('video::videos.messsage.resolutionInvalid')) :
            $this->getSuccessJsonResponse(['info' => array_shift($tempImageInfo)]);
    }
}
