<?php

namespace Contus\PartnerProgram\Api\Controllers;

use Contus\PartnerProgram\Model\PartnerProgram;
use Contus\PartnerProgram\Repositories\PartnerProgramRepository;
use Contus\Base\ApiController;

class PartnerProgramController extends ApiController {

    public function __construct(PartnerProgramRepository $partnerProgramRespository) {
        parent::__construct();
        $this->repository = $partnerProgramRespository;
        $this->repository->setRequestType(static::REQUEST_TYPE);
    }

    public function getInfo() {
        // dd(999);
        return $this->getSuccessJsonResponse([
            'success'
        ]);
    }

    public function postAdd() {
        $isCreated = false;
        if ($this->repository->addPartnerProgram()) {
            $isCreated = true;
            return ($isCreated) ? $this->getSuccessJsonResponse(['message' => trans('partner-programs::index.add.success')])
                : $this->getErrorJsonResponse([], trans('partner-programs::index.add.error'));
        }
    }

    public function postEdit($id) {
        $isUpdated = false;
        if ($this->repository->updatePartnerProgram($id)) {
            $isUpdated = true;
            return ($isUpdated) ? $this->getSuccessJsonResponse(['message' => trans('partner-programs::index.update.success')])
                : $this->getErrorJsonResponse([], trans('partner-programs::index.update.error'));
        }
    }

    public function postStatusEdit() {
        $isEdited = false;
        if ($this->repository->statusUpdate()) {
            $isEdited = true;
            return ($isEdited) ? $this->getSuccessJsonResponse(['message' => trans('partner-programs::index.status-update.success')])
                : $this->getErrorJsonResponse([], trans('partner-programs::index.status-update.error'));
        }
    }

    public function searchRecord() {
        $isSearched = false;
        $data = $this->repository->searchByName()->getData('data');
        if ($this->repository->searchByName()) {
            $isSearched = true;
            return ($isSearched) ?
                $this->getSuccessJsonResponse([
                    'message' => trans('partner-programs::index.fetch-data.success'),
                    'data' => $data['data'][0] ?? null,
                ])
                : $this->getErrorJsonResponse([], trans('partner-programs::index.fetch-data.error'));
        }
    }

    public function postRemove($id) {
        $isDeleted = false;
        if ($this->repository->recordRemove($id)) {
            $isDeleted = true;
            return ($isDeleted) ? $this->getSuccessJsonResponse([
                'message' => trans('partner-programs::index.remove.success'),
                'data' => $data['data'][0] ?? null,
            ])
                : $this->getErrorJsonResponse([], trans('partner-programs::index.remove.error'));
        }
    }
}
