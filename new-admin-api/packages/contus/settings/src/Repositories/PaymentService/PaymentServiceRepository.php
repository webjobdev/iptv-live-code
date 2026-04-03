<?php

namespace Contus\Settings\Repositories\PaymentService;

use Contus\Base\Helpers\StringLiterals;
use Contus\Base\Repository;
use Contus\Organizations\Model\OrganizationDetail;
use Contus\Settings\Model\PaymentService;
use Illuminate\Support\Facades\Log;

class PaymentServiceRepository extends Repository
{
    protected $_services;

    public function __construct(PaymentService $pametService)
    {
        parent::__construct();
        $this->_services = $pametService;
    }

    public function postCreate()
    {
        return $this->recordCreate($this->request->all());
    }

    public function recordCreate($requestData)
    {
        $this->setRules([
            'payment_provider' => 'required',
            'provider_data' => 'required|array'
        ]);
        $this->_validate();

        $create = new PaymentService();

        $create->payment_provider = $requestData['payment_provider'];
        $create->provider_data = $requestData['provider_data'];

        Log::info(json_encode($create));
        // $create->save();
        return 'success';
    }

    public function postToggle($id)
    {
        if (!empty($id)) {
            $pymService = $this->_services->find($id);

            $pymService->is_active = $this->request->is_active ? 1 : 0;
            $pymService->save();

            return response()->json([
                'success' => true,
                'message' => 'Data Update Successfully.',
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Invalid ID'], 400);
    }

    public function postDefault($id)
    {
        if (!empty($id)) {
            $pymService = $this->_services->find($id);

            $pymService->default = $this->request->default ? 1 : 0;
            $pymService->save();

            // OrganizationDetail::update(
            //     'payment_service_default' , $pymService->id
            // );

            return response()->json([
                'success' => true,
                'message' => 'Default Payment Provider Updated Successfully.',
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'Invalid ID'
        ], 400);
    }

    public function postEdit($id)
    {
        if (!empty($id)) {

            $edit = $this->_services->find($id);

            $this->setRules([
                'payment_provider' => 'required',
                'provider_data' => 'required|array'
            ]);

            $this->validate($this->request, $this->getRules());

            $edit->payment_provider = $this->request->payment_provider;
            $edit->provider_data = $this->request->provider_data;

            $edit->save();

            return true;
        }
    }

    public function prepareGrid()
    {
        $this->setGridModel($this->_services);
        return $this;
    }


    public function getGridHeadings()
    {
        return [
            'heading' => [
                ['name' => 'S.No', 'S.No' => 'SNo', 'sort' => false],
                ['name' => 'Service Type', 'value' => '', 'sort' => true],
                ['name' => 'Mode', 'value' => '', 'sort' => true],
                ['name' => 'Status', 'value' => '', 'sort' => true],
                ['name' => 'Actions', 'value' => '', 'sort' => true],
            ]
        ];
    }

    protected function searchFilter($builderCoupon)
    {
        $searchRecordUsers = $this->request->has(StringLiterals::SEARCHRECORD)
            && is_array($this->request->input(StringLiterals::SEARCHRECORD))
            ? $this->request->input(StringLiterals::SEARCHRECORD)
            : [];

        foreach ($searchRecordUsers as $key => $value) {
            if (in_array($key, ['is_active', 'is_parental']) && $value === 'all') {
                continue;
            }

            $builderCoupon = $builderCoupon->where($key, 'like', "%$value%");
        }

        return $builderCoupon;
    }
}