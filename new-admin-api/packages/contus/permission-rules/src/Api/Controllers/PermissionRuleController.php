<?php

namespace Contus\PermissionRule\Api\Controllers;

use Contus\PermissionRule\Model\PermissionRule;
use Contus\PermissionRule\Repositories\PermissionRuleRepository;
use Contus\Base\ApiController;

class PermissionRuleController extends ApiController {

    public function __construct(PermissionRuleRepository $partnerProgramRespository) {
        parent::__construct();
        $this->repository = $partnerProgramRespository;
        $this->repository->setRequestType(static::REQUEST_TYPE);
    }

    public function getInfo() {
        return $this->getSuccessJsonResponse([
            'success'
        ]);
    }

    public function postAdd() {
        $isCreated = false;
        if ($this->repository->addRulePermissions()) {
            $isCreated = true;
            return ($isCreated) ? $this->getSuccessJsonResponse(['message' => trans('permission-rules::index.add.success')])
                : $this->getErrorJsonResponse([], trans('permission-rules::index.add.error'));
        }
    }

    public function postEdit($id) {
        $isUpdated = false;
        if ($this->repository->updateRulePermissions($id)) {
            $isUpdated = true;
            return ($isUpdated) ? $this->getSuccessJsonResponse(['message' => trans('permission-rules::index.update.success')])
                : $this->getErrorJsonResponse([], trans('permission-rules::index.update.error'));
        }
    }

    public function deleteRule($id) {
        $isDeleted = false;
        if ($this->repository->deleteRuleData($id)) {
            $isDeleted = true;
            return ($isDeleted) ? $this->getSuccessJsonResponse([
                'message' => trans('permission-rules::index.delete.success'),
            ])
                : $this->getErrorJsonResponse([], trans('permission-rules::index.delete.error'));
        }
    }

    public function searchRecord() {
        $isSearched = false;
        $data = $this->repository->searchByName()->getData('data');
        if ($this->repository->searchByName()) {
            $isSearched = true;
            return ($isSearched) ?
                $this->getSuccessJsonResponse([
                    'message' => trans('permission-rules::index.fetch-data.success'),
                    'data' => $data['data'][0] ?? null,
                ])
                : $this->getErrorJsonResponse([], trans('permission-rules::index.fetch-data.error'));
        }
    }

    public function getAllowedOrgs() {
        $isFetched = false;
        $data = $this->repository->getOrgList();
        if ($data) {
            $isFetched = true;
            return ($isFetched) ? $this->getSuccessJsonResponse([
                'message' => trans('permission-rules::index.fetch-data.success'),
                'data' => $data->getData('data'),
            ]) : $this->getErrorJsonResponse([], trans('permission-rules::index.fetch-data.error'));
        }
    }
}
