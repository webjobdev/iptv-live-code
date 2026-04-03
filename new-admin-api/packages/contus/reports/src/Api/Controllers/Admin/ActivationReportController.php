<?php

namespace Contus\Reports\Api\Controllers\Admin;

use Contus\Base\ApiController;
use Contus\Base\Controller;
use Contus\Reports\Repositories\ActivationReportRepository;

class ActivationReportController extends ApiController
{
    public function __construct(ActivationReportRepository $activationReportRepository)
    {
        parent::__construct();
        $this->repository = $activationReportRepository;
        $this->repository->setRequestType(static::REQUEST_TYPE);
    }

    public function getInfo()
    {
        return $this->getSuccessJsonResponse(['success']);
    }

    public function postCreate()
    {
        $data = $this->repository->postCreate();
        if ($data == 'success') {
            return $this->getSuccessJsonResponse(['message' => 'Activation Audit Reports Created.']);
        } else {
            return $this->getErrorJsonResponse([], $data);
        }
    }

    public function postGenerate()
    {
        $data = $this->repository->postGenerate();
        if ($data == 'success') {
            return $this->getSuccessJsonResponse(['message' => 'Activation Audit Reports Generate.']);
        } else {
            return $this->getErrorJsonResponse([], $data);
        }
    }

    public function report($id)
    {
        $update = $this->repository->report($id);
        if ($update == 'success') {
            return $this->getSuccessJsonResponse(['message' => 'Data Updated.']);
        } else {
            return $this->getErrorJsonResponse([], $update);
        }
    }
}