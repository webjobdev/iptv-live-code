<?php

namespace Contus\Organizations\Api\Controller;

use Contus\Base\ApiController;
use Contus\Base\Repositories\UploadRepository;
use Contus\Organizations\Repositories\PartnerProductRepository;

class PartnerProductController extends ApiController
{
    protected $_Ppupload;

    public function __construct(PartnerProductRepository $partnerProductRepository, UploadRepository $uploadRepository)
    {
        parent::__construct();
        $this->repository = $partnerProductRepository;
        $this->_Ppupload = $uploadRepository;
        $this->repository->setRequestType(static::REQUEST_TYPE);
    }

    public function getInfo()
    {
        return $this->getSuccessJsonResponse(['success']);
    }

    public function postThumbnail()
    {
        $moduleName = 'tvshow-season-image';
        $tempImageInfo = $this->_Ppupload
            ->setModelIdentifier(UploadRepository::MODEL_IDENTIFIER_THUMBNAIL)->tempPrepare()
            ->tempUpload($moduleName, $this->request->size);

        return empty($tempImageInfo) ? $this->getErrorJsonResponse([], trans('video::videos.messsage.resolutionInvalid')) :
            $this->getSuccessJsonResponse(['info' => array_shift($tempImageInfo)]);
    }

    public function postAdd()
    {
        $ppdata = $this->repository->postAdd();
        if ($ppdata == 'success') {
            return $this->getSuccessJsonResponse(['success' => 'Partner Product Data Created.']);
        } else {
            return $this->getErrorJsonResponse([], $ppdata);
        }
    }

    public function postEdit($id)
    {
        $data = $this->repository->postEdit($id);
        return (is_null($data)) ?
            $this->getErrorJsonResponse([], null, 404) :
            $this->getSuccessJsonResponse(['response' => $data]);
    }
}
