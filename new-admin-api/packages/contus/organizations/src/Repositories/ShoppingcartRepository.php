<?php

namespace Contus\Organizations\Repositories;

use AWS\CRT\HTTP\Message;
use Contus\Base\Repository;
use Contus\Organizations\Model\OrganizationSubscription;
use Contus\Organizations\Model\ShoppingCart;
use Contus\Organizations\Model\ShoppingCartCustomPlan;
use Google\Service\ShoppingContent;

class ShoppingcartRepository extends Repository {

    protected $_cartPlans;

    public function __construct(ShoppingCartCustomPlan $shoppingCartPlans) {
        parent::__construct();
        $this->_cartPlans = $shoppingCartPlans;
    }

    public function prepareGrid() {
        $this->setGridModel($this->_cartPlans);
        return $this;
    }

    public function createCustomPlan() {
        $this->setRules([
            'name' => 'required',
            'description' => 'required',
            'cover_iamge' => 'required',
            'label' => 'required',
            'additional_info' => 'required',
        ]);

        $cartPlan = new ShoppingCartCustomPlan();
        $cartPlan->plan_name = $this->request->input('plan_name');
        $cartPlan->description = $this->request->input('plan_desc');
        $cartPlan->cover_image = $this->request->input('cover_image');
        $cartPlan->label = $this->request->input('label') == '1' ? 'Enable' : 'Disable';
        $cartPlan->additional_info = $this->request->input('additional_info');
        $cartPlan->save();

        return response()->json([
            'success' => true,
            'message' => 'Shopping Cart Plan Added Successfully.'
        ]);
    }

    public function updateCustomPlan($id) {
        $this->setRules([
            'name' => 'required',
            'description' => 'required',
            'cover_iamge' => 'required',
            'label' => 'required',
            'additional_info' => 'required',
        ]);

        $cartPlan = ShoppingCartCustomPlan::find($id);
        $cartPlan->plan_name = $this->request->input('plan_name');
        $cartPlan->description = $this->request->input('plan_desc');
        $cartPlan->cover_image = $this->request->input('cover_image');
        $cartPlan->label = $this->request->input('label') == '1' ? 'Enable' : 'Disable';
        $cartPlan->additional_info = $this->request->input('additional_info');
        $cartPlan->save();

        return response()->json([
            'success' => true,
            'message' => 'Shopping Cart Plan Updated Successfully.'
        ]);
    }

    public function deleteCustomPlan($id) {
        $plan = ShoppingCartCustomPlan::find($id);
        if ($plan) {
            $plan->delete();

            return response()->json([
                'success' => true,
                'message' => 'Plan Deleted Successfully.'
            ]);
        }
        // return;
    }

    public function updateTableData() {
        $reqData = $this->request->all();
        $data = array_values($reqData);

        foreach ($data as $item) {
            if (is_array($item) && isset($item['plan_name'])) {
                $customDroppedPlan = new ShoppingCartCustomPlan();
                $customDroppedPlan->plan_name = $item['plan_name'];
                $customDroppedPlan->save();
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Records Saved Successfully.'
        ]);
    }

    public function toggleCustomPlanStatus() {
        $customDroppedPlan = ShoppingCartCustomPlan::find($this->request->input('id'));
        if ($customDroppedPlan) {
            $customDroppedPlan->status = $this->request->input('status');
            $customDroppedPlan->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Records Saved Successfully.'
        ]);
    }
}
