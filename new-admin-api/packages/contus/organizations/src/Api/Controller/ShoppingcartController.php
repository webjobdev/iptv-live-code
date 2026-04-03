<?php

namespace Contus\Organizations\Api\Controller;

use Contus\Base\ApiController;
use Contus\Organizations\Model\ShoppingCartCustomPlan;
use Contus\Organizations\Repositories\ShoppingcartRepository;
use Illuminate\Support\Facades\Auth;

class ShoppingcartController extends ApiController {
    public function __construct(ShoppingcartRepository $shoppingcart_repository) {
        parent::__construct();
        $this->repository = $shoppingcart_repository;
        $this->repository->setRequestType(static::REQUEST_TYPE);
    }

    public function getInfo() {
        $user = Auth::user();
        return $this->getSuccessJsonResponse(['success']);
    }

    public function addCustomPlan() {
        $isCreated = false;
        if ($this->repository->createCustomPlan()) {
            $isCreated = true;
            return $this->getSuccessJsonResponse(['message' => 'Plan Added Successfully.']);
        } else {
            return $this->getSuccessJsonResponse([], 'Error occurred while Adding Plan.');
        }
    }

    public function editCustomPlan($id) {
        $isUpdated = false;
        if ($this->repository->updateCustomPlan($id)) {
            $isUpdated = true;
            return $this->getSuccessJsonResponse(['message' => 'Plan Updated Successfully.']);
        } else {
            return $this->getSuccessJsonResponse([], 'Error occurred while Updating Plan.');
        }
    }

    public function removeCustomPlan($id) {
        $isDestroyed = false;
        if ($this->repository->deleteCustomPlan($id)) {
            $isDestroyed = true;
            return $this->getSuccessJsonResponse(['message' => 'Plan Deleted Successfully.']);
        } else {
            return $this->getSuccessJsonResponse([], 'Error occurred while Deleting Plan.');
        }
    }

    public function updateTableRecords() {
        $isUpdated = false;
        if ($this->repository->updateTableData()) {
            $isUpdated = true;
            return $this->getSuccessJsonResponse(['message' => 'Table Records Updated Successfully.']);
        } else {
            return $this->getSuccessJsonResponse([], 'Error occurred while Updating Table Records.');
        }
    }

    public function updateCustomPlansStatus() {
        $isUpdated = false;
        if ($this->repository->toggleCustomPlanStatus()) {
            $isUpdated = true;
            return $this->getSuccessJsonResponse(['message' => 'Table Records Status Updated Successfully.']);
        } else {
            return $this->getSuccessJsonResponse([], 'Error occurred while Updating Status Table Records.');
        }
    }
}
