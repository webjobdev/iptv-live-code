<?php

namespace Contus\Reports\Api\Controllers\Admin;

use Contus\Base\ApiController;
use Contus\Reports\Repositories\SubscriberGenerateReportRepository;
use Contus\Reports\Repositiries\SubscriberReportRepository;

class SubscriberGenerateReportController extends ApiController
{

    public function __construct(SubscriberGenerateReportRepository $subscriberGenerateReportRepository)
    {
        parent::__construct();
        $this->repository = $subscriberGenerateReportRepository;
        $this->repository->setRequestType(static::REQUEST_TYPE);
    }

    public function getInfo()
    {
        return $this->getSuccessJsonResponse(['success']);
    }
}