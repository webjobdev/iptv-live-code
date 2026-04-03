<?php

namespace Contus\Organizations\Api\Controller;

use Contus\Base\ApiController;
use Contus\Organizations\Repositories\MonetizationPlanssRepository;
use Illuminate\Support\Facades\Auth;

class MonetizationPlanssController extends ApiController
{

    public function __construct(MonetizationPlanssRepository $monetizationPlanssRepository)
    {
        parent::__construct();
        $this->repository = $monetizationPlanssRepository;
        $this->repository->setRequestType(static::REQUEST_TYPE);
    }

    public function getInfo()
    {
        $user = Auth::user();
        return $this->getSuccessJsonResponse(['success']);
    }

    public function postadd()
    {
        $isCreated = false;
        if ($this->repository->postCreate()) {
            $isCreated = true;
            return $this->getSuccessJsonResponse(['message' => 'Channel Content Sets created successfully']);
        } else {
            return $this->getErrorJsonResponse([], 'Error Occurred in adding monetization Planss');
        }
    }

    public function postEdit($id)
    {
        $isUpdated = false;
        if ($this->repository->postEdit($id)) {
            $isUpdated = true;
            return $this->getSuccessJsonResponse(['message' => 'Channel Content Sets Updated successfully']);
        } else {
            return $this->getErrorJsonResponse([], 'Error Occurred in updated monetization Planss');
        }
    }

    public function statusUpdate()
    {
        $isUpdated = false;
        if ($this->repository->updateStatus()) {
            $isUpdated = true;
            return $this->getSuccessJsonResponse(['message' => 'Status Updated Successfully.']);
        } else {
            return $this->getErrorJsonResponse([], 'Error Occurred in updating status.');
        }
    }

    public function removePlan($id)
    {
        $isDestroyed = false;
        if ($this->repository->deletePlan($id)) {
            $isDestroyed = true;
            return $this->getSuccessJsonResponse(['message' => 'Plan Deleted Successfully.']);
        } else {
            return $this->getErrorJsonResponse([], 'Error Occurred in deleting plan.');
        }
    }

    public function togglePublishNow($id)
    {
        $isUpdated = false;

        if ($this->repository->togglePublishNow($id)) {
            $isUpdated = true;
            return ($isUpdated) ?
                $this->getSuccessJsonResponse(['message' => 'Monetization Plan Data Update Successfully.']) :
                $this->getErrorJsonResponse([], 'Monetization Plan Data Not Update.');
        }
    }
}
