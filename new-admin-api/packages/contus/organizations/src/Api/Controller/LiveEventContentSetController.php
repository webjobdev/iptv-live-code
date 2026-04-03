<?php

namespace Contus\Organizations\Api\Controller;

use Contus\Base\ApiController;
use Contus\Base\Helpers\StringLiterals;
use Contus\Base\Repositories\UploadRepository;
use Contus\Organizations\Repositories\LiveEventContetntSetRepository;
use Illuminate\Support\Facades\Auth;
use Contus\Organizations\Repositories\ContetntSetRepository;

class LiveEventContentSetController extends ApiController
{
    protected $_eventUpload;
    public function __construct(LiveEventContetntSetRepository $liveEventContetntSetRepository, UploadRepository $uploadRepository)
    {
        parent::__construct();
        $this->repository = $liveEventContetntSetRepository;
        $this->_eventUpload = $uploadRepository;
        $this->repository->setRequestType(static::REQUEST_TYPE);
    }

    public function getInfo()
    {
        $user = Auth::user();
        return $this->getSuccessJsonResponse(['success']);
    }

    public function postAdd()
    {
        $channelData = $this->repository->Eventset();
        if ($channelData == 'success') {
            return $this->getSuccessJsonResponse(['message' => 'Channel Content Sets created successfully']);
        } else {
            return $this->getErrorJsonResponse([], $channelData);
        }
    }

    public function postPosters()
    {
        $moduleName = 'tvshow-season-episode-image';
        $tempImageInfo = $this->_eventUpload
            ->setModelIdentifier(UploadRepository::MODEL_IDENTIFIER_POSTER)->tempPrepare()
            ->tempUpload($moduleName, $this->request->size);

        return empty($tempImageInfo) ? $this->getErrorJsonResponse([], trans(StringLiterals::UNABLE_TO_UPLOAD)) :
            $this->getSuccessJsonResponse(['info' => array_shift($tempImageInfo)]);
    }

    public function postEdit($id)
    {
        $channelId = $this->repository->postEdit($id);
        if ($channelId == 'success') {
            return $this->getSuccessJsonResponse(['message' => 'Channel Content Sets Updated successfully']);
        } else {
            return $this->getErrorJsonResponse([], $channelId);
        }
    }

}