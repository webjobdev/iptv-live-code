<?php

namespace Contus\Settings\Api\Controllers\Admin\Extensions;

use Contus\Base\ApiController;
use Contus\Settings\Repositories\Extensions\DeviceRedirectRepositoriy;

class DeviceRedirectController extends ApiController
{
    public function __construct(DeviceRedirectRepositoriy $deviceRedirectRepositoriy)
    {
        parent::__construct();
        $this->repository = $deviceRedirectRepositoriy;
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

}