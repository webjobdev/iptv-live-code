<?php

namespace Contus\Reports\Api\Controllers\Admin;

use Contus\Base\ApiController;
use Contus\Reports\Repositories\SubscriberReportRepository;

class SubscriberReportController extends ApiController
{

    public function __construct(SubscriberReportRepository $subscriberReportRepository)
    {
        parent::__construct();
        $this->repository = $subscriberReportRepository;
        $this->repository->setRequestType(static::REQUEST_TYPE);
    }

    public function getInfo()
    {
        return $this->getSuccessJsonResponse(['success']);
    }

    public function postCreate()
    {
        $create = $this->repository->postCreate();
        if ($create == 'success') {
            return $this->getSuccessJsonResponse(['message' => 'Subscriber Report Create.']);
        } else {
            return $this->getErrorJsonResponse([], $create);
        }
    }

    public function postGenerate()
    {
        $Generate = $this->repository->postGenerate();
        if ($Generate == 'success') {
            return $this->getSuccessJsonResponse(['message' => 'Subscriber Report Generate.']);
        } else {
            return $this->getErrorJsonResponse([], $Generate);
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

    public function downloadPdf($id)
    {
        $pdf = $this->repository->savepdf($id);
        if ($pdf == 'success') {
            return $this->getSuccessJsonResponse(['message' => 'Pdf Saved.']);
        } else {
            return $this->getErrorJsonResponse([], $pdf);
        }
    }
}