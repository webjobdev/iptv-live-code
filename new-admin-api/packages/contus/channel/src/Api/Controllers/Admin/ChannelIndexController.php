<?php

namespace Contus\Channel\Api\Controllers\Admin;

use Contus\Base\ApiController;
use Contus\Base\Repositories\UploadRepository;
use Contus\Channel\Repositories\ChannelRepository;

class ChannelIndexController extends ApiController
{

    protected $_channelUpload;

    public function __construct(ChannelRepository $channelRepository, UploadRepository $uploadRepository)
    {
        parent::__construct();
        $this->repository = $channelRepository;
        $this->_channelUpload = $uploadRepository;
        $this->repository->setRequestType(static::REQUEST_TYPE);
    }

    public function getInfo()
    {
        return $this->getSuccessJsonResponse(['success']);
    }

    public function postPosters()
    {
        $moduleName = 'channel-logo';
        $tempImageInfo = $this->_channelUpload
            ->setModelIdentifier(UploadRepository::MODEL_IDENTIFIER_POSTER)->tempPrepare()
            ->tempUpload($moduleName, $this->request->size);
        return empty($tempImageInfo) ? $this->getErrorJsonResponse([], trans('video::videos.messsage.resolutionInvalid')) :
            $this->getSuccessJsonResponse(['info' => array_shift($tempImageInfo)]);
    }

    public function CreateChannel()
    {
        $channelData = $this->repository->Channel();
        if ($channelData == 'success') {
            return $this->getSuccessJsonResponse(['message' => 'Channel created successfully']);
        } else {
            return $this->getErrorJsonResponse([], $channelData);
        }
    }

    public function getChannelToEdit($id)
    {
        $channelId = $this->repository->getChannel($id);
        return (is_null($channelId)) ?
            $this->getErrorJsonResponse([], null, 404) :
            $this->getSuccessJsonResponse(['response' => $channelId]);
    }

    public function postEdit($id)
    {
        $isUpdated = false;

        if ($this->repository->channelUpdate($id)) {
            $isUpdated = true;
            return ($isUpdated) ?
                $this->getSuccessJsonResponse(['message' => 'Channel Data Update Successfully.']) :
                $this->getErrorJsonResponse([], 'Channel Data Not Update.');
        }
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

    public function postBulkFetch()
    {
        $channelData = $this->repository->bulkFetch();
        if ($channelData == 'success') {
            return $this->getSuccessJsonResponse(['message' => 'Channel created successfully']);
        } else {
            return $this->getErrorJsonResponse([], $channelData);
        }
    }
}