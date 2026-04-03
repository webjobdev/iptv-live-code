<?php

namespace Contus\ChannelServices\Api\Controllers\Admin;

use Contus\Base\ApiController;
use Contus\ChannelServices\Repositories\CatchUpIndexRepository;

class CatchUpIndexController extends ApiController
{
    public function __construct(CatchUpIndexRepository $catchUpIndexRepository)
    {
        parent::__construct();
        $this->repository = $catchUpIndexRepository;
        $this->repository->setRequestType(static::REQUEST_TYPE);
    }

    public function getInfo()
    {
        return $this->getSuccessJsonResponse(['success']);
    }

    public function CreateCatchUp()
    {
        $isCreated = false;
        if ($this->repository->Create()) {
            $isCreated = true;
            return ($isCreated) ?
                $this->getSuccessJsonResponse(['message' => 'Catch Up Data Created Successfully.']) :
                $this->getErrorJsonResponse([], 'Catch Up Data Created.');
        }
    }

    public function postEdit($id)
    {
        $catchUpId = $this->repository->postEdit($id);
        return (is_null($catchUpId)) ?
            $this->getErrorJsonResponse([], 'Data Not Update.', 404) :
            $this->getSuccessJsonResponse(['message' => 'Catch Up Data Updated.']);
    }

    public function postToggleEdit($id)
    {
        $isUpdated = true;
        if ($this->repository->postToggle($id)) {
            return ($isUpdated) ?
                $this->getSuccessJsonResponse(['message' => 'Catch Up Update Successfully.']) :
                $this->getErrorJsonResponse([], 'Catch Up Not Update.');
        }
    }
}
