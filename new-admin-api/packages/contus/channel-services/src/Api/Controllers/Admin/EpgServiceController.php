<?php

namespace Contus\ChannelServices\Api\Controllers\Admin;

use Contus\Base\ApiController;
use Contus\ChannelServices\Repositories\EpgServiceRepository;

class EpgServiceController extends ApiController
{
    public function __construct(EpgServiceRepository $epgServiceRepository)
    {
        parent::__construct();
        $this->repository = $epgServiceRepository;
        $this->repository->setRequestType(static::REQUEST_TYPE);
    }

    public function getInfo()
    {
        return $this->getSuccessJsonResponse(['success']);
    }

    public function Create()
    {
        $isCreated = false;
        if ($this->repository->create()) {
            $isCreated = true;
            return ($isCreated) ?
                $this->getSuccessJsonResponse(['message' => 'Epg Data Saved.']) :
                $this->getErrorJsonResponse([], 'Data Not Save.');
        }
    }

    public function postEdit($id)
    {
        $isUpdated = false;
        if ($this->repository->postEdit($id)) {
            $isUpdated = true;
            return ($isUpdated) ?
                $this->getSuccessJsonResponse(['message' => 'Epg Data Updated.']) :
                $this->getErrorJsonResponse([], 'Data Not Updated.');
        }
    }

    public function postToggleEdit($id)
    {
        $isUpdated = false;
        if ($this->repository->postToggleEdit($id)) {
            $isUpdated = true;
            return ($isUpdated) ?
                $this->getSuccessJsonResponse(['message' => 'Epg Data Updated.']) :
                $this->getErrorJsonResponse([], 'Data Not Updated.');
        }
    }

    public function postRun($id)
    {
        return $this->repository->postRun($id);
    }
}