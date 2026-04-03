<?php

namespace Contus\ApiAccess\Api\Controllers;

use Contus\ApiAccess\Model\ApiAccess;
use Contus\ApiAccess\Repositories\ApiAccessRepository;
use Contus\Base\ApiController;
use Contus\Organizations\Model\Organization;

class ApiAccessController extends ApiController
{

    public function __construct(ApiAccessRepository $apiAccessRespository)
    {
        parent::__construct();
        $this->repository = $apiAccessRespository;
        $this->repository->setRequestType(static::REQUEST_TYPE);
    }

    public function getInfo()
    {
        return $this->getSuccessJsonResponse([
            'success'
        ]);
    }

    // public function postRecords() {
    //     $isCreated = false;
    //     if ($this->repository->prepareGrid()) {
    //         $isCreated = true;
    //         return ($isCreated) ? $this->getSuccessJsonResponse(['message' => trans('api-access::index.add.success')])
    //             : $this->getErrorJsonResponse([], trans('api-access::index.add.error'));
    //     }
    // }

    public function postAdd()
    {
        $isCreated = false;
        if ($this->repository->addApiUser()) {
            $isCreated = true;
            return ($isCreated) ? $this->getSuccessJsonResponse(['message' => trans('api-access::index.add.success')])
                : $this->getErrorJsonResponse([], trans('api-access::index.add.error'));
        }
    }

    public function postEdit($id)
    {
        $isUpdated = false;
        if ($this->repository->updateApiUser($id)) {
            $isUpdated = true;
            return ($isUpdated) ? $this->getSuccessJsonResponse(['message' => trans('api-access::index.update.success')])
                : $this->getErrorJsonResponse([], trans('api-access::index.update.error'));
        }
    }

    public function postStatusEdit()
    {
        $isEdited = false;
        if ($this->repository->statusUpdate()) {
            $isEdited = true;
            return ($isEdited) ? $this->getSuccessJsonResponse(['message' => trans('api-access::index.status-update.success')])
                : $this->getErrorJsonResponse([], trans('api-access::index.status-update.error'));
        }
    }

    public function postRemove($id)
    {
        $isRemoved = false;
        if ($this->repository->removeApiAccess($id)) {
            $isRemoved = true;
            return ($isRemoved) ? $this->getSuccessJsonResponse(['message' => trans('api-access::index.remove.success')])
                : $this->getErrorJsonResponse([], trans('api-access::index.remove.error'));
        }
    }

    public function searchRecord()
    {
        $isEdited = false;
        $data = $this->repository->searchByName()->getData('data');
        if ($this->repository->searchByName()) {
            $isEdited = true;
            return ($isEdited) ?
                $this->getSuccessJsonResponse([
                    'message' => trans('api-access::index.fetch-data.success'),
                    'data' => $data['data'][0] ?? null,
                ])
                : $this->getErrorJsonResponse([], trans('api-access::index.fetch-data.error'));
        }
    }

    public function postMonPlan()
    {
        $org = Organization::with('monPlan')
            ->paginate(10);

        return response()->json([
            'data' => $org
        ]);
    }
}
