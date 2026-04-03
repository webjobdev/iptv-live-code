<?php

namespace Contus\ChannelServices\Api\Controllers\Admin;

use Contus\Base\ApiController;
use Contus\ChannelServices\Repositories\LiveRewindRepository;

class LiveRewindController extends ApiController
{

    public function __construct(LiveRewindRepository $liveRewindRepository)
    {
        parent::__construct();
        $this->repository = $liveRewindRepository;
        $this->repository->setRequestType(static::REQUEST_TYPE);
    }

    public function getInfo()
    {
        return $this->getSuccessJsonResponse(['success']);
    }

    public function CreateRewind()
    {
        $isCreated = false;
        if ($this->repository->create()) {
            $isCreated = true;
            return ($isCreated) ?
                $this->getSuccessJsonResponse(['message' => 'Live Rewind Data Saved.']) :
                $this->getErrorJsonResponse([], 'Data Not Save.');
        }
    }

    public function postEdit($id)
    {
        $isUpdated = false;
        if ($this->repository->postEdit($id)) {
            $isUpdated = true;
            return ($isUpdated) ?
                $this->getSuccessJsonResponse(['message' => 'Live Rewind Data Updated.']) :
                $this->getErrorJsonResponse([], 'Data Not Updated.');
        }
    }

    public function postToggleEdit($id)
    {
        $isUpdated = false;
        if ($this->repository->postToggleEdit($id)) {
            $isUpdated = true;
            return ($isUpdated) ?
                $this->getSuccessJsonResponse(['message' => 'Live Rewind Data Updated.']) :
                $this->getErrorJsonResponse([], 'Data Not Updated.');
        }
    }
}
