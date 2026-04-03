<?php

namespace Contus\Organizations\Api\Controller\AppCustomization;

use BadMethodCallException;
use Contus\Base\ApiController;
use Contus\Base\Helpers\StringLiterals;
use Contus\Base\Repositories\UploadRepository;
use Contus\Organizations\Repositories\AppCustomization\BannerCarouselsSubscriptionRepository;

class BannerCarouselsSubscription extends ApiController
{

    protected $_bannercs;

    public function __construct(BannerCarouselsSubscriptionRepository $bannerCarouselsSubscriptionRepository, UploadRepository $uploadRepository)
    {
        parent::__construct();
        $this->repository = $bannerCarouselsSubscriptionRepository;
        $this->_bannercs = $uploadRepository;
        $this->repository->setRequestType(static::REQUEST_TYPE);
    }

    public function getInfo()
    {
        return $this->getSuccessJsonResponse(['success']);
    }

    public function postThumbnail()
    {
        $moduleName = 'tvshow-season-episode-image';
        $tempImageInfo = $this->_bannercs
            ->setModelIdentifier(UploadRepository::MODEL_IDENTIFIER_THUMBNAIL)->tempPrepare()
            ->tempUpload($moduleName, $this->request->size);

        return empty($tempImageInfo) ? $this->getErrorJsonResponse([], trans('video::videos.messsage.resolutionInvalid')) :
            $this->getSuccessJsonResponse(['info' => array_shift($tempImageInfo)]);
    }

    public function postPosters()
    {
        $moduleName = 'tvshow-season-episode-image';
        $tempImageInfo = $this->_bannercs
            ->setModelIdentifier(UploadRepository::MODEL_IDENTIFIER_POSTER)->tempPrepare()
            ->tempUpload($moduleName, $this->request->size);

        return empty($tempImageInfo) ? $this->getErrorJsonResponse([], trans(StringLiterals::UNABLE_TO_UPLOAD)) :
            $this->getSuccessJsonResponse(['info' => array_shift($tempImageInfo)]);
    }

    public function postAdd()
    {
        $insert = $this->repository->postAdd();
        if ($insert == 'success') {
            return $this->getSuccessJsonResponse(['message' => 'Banner Carousel Created Successfully.']);
        } else {
            return $this->getErrorJsonResponse([], $insert);
        }
    }

    public function postEdit($id)
    {
        $edit = $this->repository->postEdit($id);
        return (is_null($edit)) ?
            $this->getErrorJsonResponse([], null, 404) :
            $this->getSuccessJsonResponse(['message' => 'Channel Listing Updated Successfully.']);
    }

    public function postRecords()
    {
        $response = ['data' => $this->repository->fetchRecord()];
        if ($this->request->input('intialRequest') == 1) {
            $response['heading'] = $this->repository->getGridHeadings();
            $response['moreInfo'] = $this->repository->getGridAdditionalInformation();
            $response['recordsCount'] = $this->repository->getCount();
        }
        return $this->getSuccessJsonResponse($response);
    }


}