<?php

namespace Contus\Subscribers\Api\Controllers\Admin;

use Barryvdh\DomPDF\Facade\Pdf;
use Contus\Base\ApiController;
use Contus\Subscribers\Model\OrgSubscribers;
use Contus\Subscribers\Repositories\ActivationRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ActivationSubscriberController extends ApiController
{
    public function __construct(ActivationRepository $activationRepository)
    {
        parent::__construct();
        $this->repository = $activationRepository;
        $this->repository->setRequestType(static::REQUEST_TYPE);
    }

    public function getInfo()
    {
        return $this->getSuccessJsonResponse([
            'data' => [
                'data' => $this->repository->fetch_alldata(),
            ]
        ]);
    }

    public function assigneInfo()
    {
        return $this->getSuccessJsonResponse([
            'info' => [
                'assigned_device_info' => $this->repository->assigned_device_info(),
            ]
        ]);
    }

    public function postAdd()
    {
        $isCreated = false;
        if ($this->repository->addDeviceSlot()) {
            $isCreated = true;
            return ($isCreated) ? $this->getSuccessJsonResponse(['message' => trans('subscribers::index.add.success')])
                : $this->getErrorJsonResponse([], trans('subscribers::index.add.error'));
        }
    }

    public function refund()
    {
        $isCreated = false;
        if ($this->repository->paymentRefund()) {
            $isCreated = true;
            return ($isCreated) ? $this->getSuccessJsonResponse(['message' => trans('subscribers::index.refund.success')]) :
                $this->getErrorJsonResponse([], trans('subscribers::index.refund.error'));
        }
    }

    public function paymentCancel()
    {
        $isCreated = false;
        if ($this->repository->paymentfailure()) {
            $isCreated = true;
            return ($isCreated) ? $this->getSuccessJsonResponse(['message' => trans('subscribers::index.cancel.success')]) :
                $this->getErrorJsonResponse([], trans('subscribers::index.cancel.error'));
        }
    }

    public function postAssignedDevice()
    {
        $isCreated = false;
        if ($this->repository->postAssignedDevice()) {
            $isCreated = true;
            return ($isCreated) ? $this->getSuccessJsonResponse(['message' => trans('subscribers::index.device_assigned.success')])
                : $this->getErrorJsonResponse([], trans('subscribers::index.device_assigned.error'));
        }
    }
}
