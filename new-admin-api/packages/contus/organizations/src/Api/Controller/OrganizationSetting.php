<?php

namespace Contus\Organizations\Api\Controller;

use Contus\Base\ApiController;
use Contus\Organizations\Repositories\OrganizationSettingRepository;

class OrganizationSetting extends ApiController
{

    public function __construct(OrganizationSettingRepository $orgSettingRepository)
    {
        parent::__construct();
        $this->repository = $orgSettingRepository;
        $this->repository->setRequestType(static::REQUEST_TYPE);
    }

    public function getInfo()
    {
        return $this->getSuccessJsonResponse(['success']);
    }

    public function postAdd()
    {
        $setting = $this->repository->postAdd();
        if ($setting == 'success') {
            return $this->getSuccessJsonResponse(['message' => 'Setting Data Insert Successfully.']);
        } else {
            return $this->getErrorJsonResponse([], $setting);
        }
    }

    public function postEdit($id)
    {
        $edit = $this->repository->postEdit($id);
        return (is_null($edit)) ?
            $this->getErrorJsonResponse([], 'Data Not Update.', 404) :
            $this->getSuccessJsonResponse(['message' => 'General Data Updated.']);
    }
}