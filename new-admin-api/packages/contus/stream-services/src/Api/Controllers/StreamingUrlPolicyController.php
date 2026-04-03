<?php

namespace Contus\StreamServices\Api\Controllers;

use Contus\StreamServices\Model\StreamServices;
use Contus\StreamServices\Repositories\StreamingUrlPolicyRepository;
use Contus\Base\ApiController;

class StreamingUrlPolicyController extends ApiController {

    public function __construct(StreamingUrlPolicyRepository $streamServicesRespository) {
        parent::__construct();
        $this->repository = $streamServicesRespository;
        $this->repository->setRequestType(static::REQUEST_TYPE);
    }

    public function getInfo() {
        return $this->getSuccessJsonResponse([
            'success'
        ]);
    }

    public function postAdd() {
        $isCreated = false;
        if ($this->repository->addStreamUrlPolicy()) {
            $isCreated = true;
            return ($isCreated) ? $this->getSuccessJsonResponse(['message' => trans('stream-services::index.policy_add.success')])
                : $this->getErrorJsonResponse([], trans('stream-services::index.policy_add.error'));
        }
    }

    public function postEdit($id) {
        $isUpdated = false;
        if ($this->repository->updateStreamUrlPolicy($id)) {
            $isUpdated = true;
            return ($isUpdated) ? $this->getSuccessJsonResponse(['message' => trans('stream-services::index.policy_update.success')])
                : $this->getErrorJsonResponse([], trans('stream-services::index.policy_update.error'));
        }
    }

    public function postView($id) {
        $isViewed = false;
        $data = $this->repository->viewStreamUrlPolicy($id)->getData('data');
        if ($this->repository->viewStreamUrlPolicy($id)) {
            $isViewed = true;
            return ($isViewed) ? $this->getSuccessJsonResponse([
                'message' => trans('stream-services::index.fetch-data.success'),
                'data' => $data['data'] ?? null
            ])
                : $this->getErrorJsonResponse([], trans('stream-services::index.fetch-data.error'));
        }
    }

    public function postStatusEdit() {
        $isEdited = false;
        if ($this->repository->statusUpdate()) {
            $isEdited = true;
            return ($isEdited) ? $this->getSuccessJsonResponse(['message' => trans('stream-services::index.status-update.success')])
                : $this->getErrorJsonResponse([], trans('stream-services::index.status-update.error'));
        }
    }

    public function removeRecord($id) {
        $isEdited = false;
        if ($this->repository->deleteRecord($id)) {
            $isEdited = true;
            return ($isEdited) ?
                $this->getSuccessJsonResponse([
                    'message' => trans('stream-services::index.policy_delete.success'),
                    // 'data' => $data['data'] ?? null,
                ])
                : $this->getErrorJsonResponse([], trans('stream-services::index.policy_delete.error'));
        }
    }
    
    public function searchRecord() {
        $isEdited = false;
        $data = $this->repository->searchByName()->getData('data');
        if ($this->repository->searchByName()) {
            $isEdited = true;
            return ($isEdited) ?
                $this->getSuccessJsonResponse([
                    'message' => trans('stream-services::index.fetch-data.success'),
                    'data' => $data['data'] ?? null,
                ])
                : $this->getErrorJsonResponse([], trans('stream-services::index.fetch-data.error'));
        }
    }
}
