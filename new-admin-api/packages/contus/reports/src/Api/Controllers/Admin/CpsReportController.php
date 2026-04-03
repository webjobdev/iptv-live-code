<?php

namespace Contus\Reports\Api\Controllers\Admin;

use Contus\Base\ApiController;
use Contus\Base\Controller;
use Contus\Reports\Repositories\CpsReportRepository;

class CpsReportController extends ApiController
{
    public function __construct(CpsReportRepository $cpsReportRepository)
    {
        parent::__construct();
        $this->repository = $cpsReportRepository;
        $this->repository->setRequestType(static::REQUEST_TYPE);
    }

    public function getInfo()
    {
        return $this->getSuccessJsonResponse(['success']);
    }

    public function postCreate()
    {
        $cps = $this->repository->postCreate();
        if ($cps == 'success') {
            return $this->getSuccessJsonResponse(['message' => 'Cps Report Create.']);
        } else {
            return $this->getErrorJsonResponse([], $cps);
        }
    }

    public function RecordFetch()
    {
        return $this->getSuccessJsonResponse([
            'data' => [
                'cps' => $this->repository->GetCpsData(),
            ]
        ]);
    }
}