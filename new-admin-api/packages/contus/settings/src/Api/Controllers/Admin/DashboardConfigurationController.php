<?php

namespace Contus\Settings\Api\Controllers\Admin;

use Contus\Base\ApiController;
use Contus\Settings\Repositories\DashboardConfigurationRepository;

class DashboardConfigurationController extends ApiController
{
    public function __construct(DashboardConfigurationRepository $dashboardConfigurationRepository)
    {
        parent::__construct();
        $this->repository = $dashboardConfigurationRepository;
        $this->repository->setRequestType(static::REQUEST_TYPE);
    }

    public function getInfo()
    {
        return $this->getSuccessJsonResponse();
    }

    public function postUpdate()
    {
        $isupdate = true;
        if ($this->repository->postUpdate()) {
            return ($isupdate) ?
                $this->getSuccessJsonResponse(['message' => 'Data Updated Successfully.']) :
                $this->getErrorJsonResponse([], 'Data not update.');
        }
    }
}