<?php

namespace Contus\Organizations\Api\Controller;

use Contus\Base\ApiController;
use Contus\Base\Helpers\StringLiterals;
use Contus\Base\Repositories\UploadRepository;
use Illuminate\Support\Facades\Auth;
use Contus\Organizations\Repositories\ContetntSetRepository;

class ChannelContentSetController extends ApiController
{
    protected $_chnlsetUpload;
    public function __construct(ContetntSetRepository $contetntSetRepository, UploadRepository $uploadRepository)
    {
        parent::__construct();
        $this->repository = $contetntSetRepository;
        $this->_chnlsetUpload = $uploadRepository;
        $this->repository->setRequestType(static::REQUEST_TYPE);
    }

    public function getInfo()
    {
        $user = Auth::user();
        return $this->getSuccessJsonResponse(['success']);
    }

    public function postAdd()
    {
        $channelData = $this->repository->Channelset();
        if ($channelData == 'success') {
            return $this->getSuccessJsonResponse(['message' => 'Channel Content Sets created successfully']);
        } else {
            return $this->getErrorJsonResponse([], $channelData);
        }
    }

    public function postPosters()
    {
        $moduleName = 'tvshow-season-episode-image';
        $tempImageInfo = $this->_chnlsetUpload
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
