<?php

namespace Contus\Settings\Api\Controllers\Admin\Extensions;

use Contus\Base\ApiController;
use Contus\Settings\Repositories\Extensions\PlayBackTokenRepositoriy;

class PlayBackTokenController extends ApiController
{
    public function __construct(PlayBackTokenRepositoriy $pbtRepository)
    {
        parent::__construct();
        $this->repository = $pbtRepository;
        $this->repository->setRequestType(static::REQUEST_TYPE);
    }

    public function getInfo()
    {
        $this->getSuccessJsonResponse(['success']);
    }

    public function postAdd()
    {
        $create = $this->repository->postAdd();
        if ($create == 'success') {
            return $this->getSuccessJsonResponse(['message' => 'Payment currency created successfully.']);
        } else {
            return $this->getErrorJsonResponse([], $create);
        }
    }

    public function postEdit($id)
    {
        $isUpdated = true;
        if ($this->repository->postEdit($id)) {
            return ($isUpdated) ?
                $this->getSuccessJsonResponse(['message' => 'Data Update Successfully.']) :
                $this->getErrorJsonResponse([], 'Data Not Update.');
        }
    }

    public function postToggle($id)
    {
        $isUpdated = true;
        if ($this->repository->postToggle($id)) {
            return ($isUpdated) ?
                $this->getSuccessJsonResponse(['message' => 'Data Update Successfully.']) :
                $this->getErrorJsonResponse([], 'Data Not Update.');
        }
    }

}