<?php

namespace Contus\Organizations\Api\Controller\AppCustomization;

use Contus\Base\ApiController;
use Contus\Base\Repositories\UploadRepository;
use Contus\Organizations\Repositories\AppCustomization\AppCustomiztionBannerCarouselRepository;

class AppCustomiztionBannerCarouselController extends ApiController
{

    protected $_banner;

    public function __construct(AppCustomiztionBannerCarouselRepository $appCustomiztionBannerCarouselRepository, UploadRepository $uploadRepository)
    {
        parent::__construct();
        $this->repository = $appCustomiztionBannerCarouselRepository;
        $this->_banner = $uploadRepository;
        $this->repository->setRequestType(static::REQUEST_TYPE);
    }

    public function postEdit($id)
    {
        $banner = $this->repository->postEdit($id);
        if ($banner) {
            return $this->getSuccessJsonResponse(['success' => 'Banner Carousel Data Updated.']);
        } else {
            return $this->getErrorJsonResponse([], 'Featured Row Not Found');
        }
    }

    public function postThumbnail()
    {
        $moduleName = 'tvshow-season-episode-image';
        $tempImageInfo = $this->_banner
            ->setModelIdentifier(UploadRepository::MODEL_IDENTIFIER_THUMBNAIL)->tempPrepare()
            ->tempUpload($moduleName, $this->request->size);

        return empty($tempImageInfo) ? $this->getErrorJsonResponse([], trans('video::videos.messsage.resolutionInvalid')) :
            $this->getSuccessJsonResponse(['info' => array_shift($tempImageInfo)]);
    }

    public function postToggle($id)
    {
        $isUpdated = true;
        if ($this->repository->postToggle($id)) {
            return ($isUpdated) ?
                $this->getSuccessJsonResponse(['message' => 'Channel Data Update Successfully.']) :
                $this->getErrorJsonResponse([], 'Channel Data Not Update.');
        }
    }

    public function postDelete($id)
    {
        $isDeleted = true;
        if ($this->repository->postDelete($id)) {
            return ($isDeleted) ?
                $this->getSuccessJsonResponse(['message' => 'Banner Delete Successfully.']) :
                $this->getErrorJsonResponse([], 'Banner Data Not Update.');
        }
    }
}