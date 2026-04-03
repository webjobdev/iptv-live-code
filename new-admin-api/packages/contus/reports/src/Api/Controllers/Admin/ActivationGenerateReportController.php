<?php

namespace Contus\Reports\Api\Controllers\Admin;

use Contus\Base\ApiController;
use Contus\Base\Controller;
use Contus\Reports\Repositories\ActivationGenerateReportRepository;

class ActivationGenerateReportController extends ApiController
{
    public function __construct(ActivationGenerateReportRepository $activationGenerateReportRepository)
    {
        parent::__construct();
        $this->repository = $activationGenerateReportRepository;
        $this->repository->setRequestType(static::REQUEST_TYPE);
    }

    
}