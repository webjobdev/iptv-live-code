<?php

namespace Contus\Reports\Repositories;

use Contus\Base\Helpers\StringLiterals;
use Contus\Base\Repository;
use Contus\Reports\Model\Activation;
use Contus\User\Models\User;
use Illuminate\Support\Facades\Auth;

class ActivationReportRepository extends Repository
{
    protected $_activation;
    public function __construct(Activation $activation)
    {
        parent::__construct();
        $this->_activation = $activation;
    }

    public function postCreate()
    {
        $create = $this->create($this->request->all());
        return $create;
    }

    public function create($request)
    {
        $user = Auth::user();
        
        $this->setRules([
            'report_name' => 'required',
            'report_period' => 'required',
            'organization' => 'required',
            'users' => 'nullable|required',
            'subscription_plan' => 'nullable|required',
            'subscription_plan_type' => 'nullable',
            'subscription_length_from_date' => 'nullable',
            'subscription_length_to_date' => 'nullable',
            'payment_service' => 'nullable',
            'autopay' => 'nullable',
            'available_plan' => 'nullable',
            'generate' => 'nullable|required',
        ]);

        $this->_validate();

        $save = new Activation();

        $save->report_name = $request['report_name'];
        $save->report_period = $request['report_period'];
        $save->organization = $request['organization'];
        $save->users = $request['users'];
        $save->subscription_plan = $request['subscription_plan'];
        $save->subscription_plan_type = $request['subscription_plan_type'] ?? null;
        $save->subscription_length_from_date = $request['subscription_length_from_date'] ?? null;
        $save->subscription_length_to_date = $request['subscription_length_to_date'] ?? null;
        $save->payment_service = $request['payment_service'] ?? null;
        $save->autopay = $request['autopay'] ?? null;
        $save->available_plan = $request['available_plan'] ?? null;
        $save->generate = $request['generate'];
        $save->created_by = $user->id;

        $save->save();

        return 'success';
    }

    public function postGenerate()
    {
        $generate = $this->generate($this->request->all());
        return $generate;
    }

    public function generate($request)
    {
        $user = Auth::user();
        $this->setRules([
            'report_name' => 'nullable|required',
            'report_period' => 'nullable|required',
            'organization' => 'nullable|required',
            'users' => 'nullable|required',
            'subscription_plan' => 'nullable|required',
            'subscription_plan_type' => 'nullable',
            'subscription_length_from_date' => 'nullable',
            'subscription_length_to_date' => 'nullable',
            'payment_service' => 'nullable',
            'autopay' => 'nullable',
            'available_plan' => 'nullable',
            'generate' => 'nullable|required',
        ]);

        $this->_validate();

        $generate = new Activation();

        $generate->report_name = $request['report_name'];
        $generate->report_period = $request['report_period'];
        $generate->organization = $request['organization'];
        $generate->users = $request['users'];
        $generate->subscription_plan = $request['subscription_plan'];
        $generate->subscription_plan_type = $request['subscription_plan_type'] ?? null;
        $generate->subscription_length_from_date = $request['subscription_length_from_date'] ?? null;
        $generate->subscription_length_to_date = $request['subscription_length_to_date'] ?? null;
        $generate->payment_service = $request['payment_service'] ?? null;
        $generate->autopay = $request['autopay'] ?? null;
        $generate->available_plan = $request['available_plan'] ?? null;
        $generate->generate = $request['generate'];
        $generate->created_by = $user->id;

        $generate->save();

        return 'success';
    }

    public function report($id)
    {
        if (!empty($id)) {
            $data = $this->_activation->findOrFail($id);

            $data->generate = $this->request->generate ? 1 : 0;
            $data->save();

            return 'success';
        } else {
            return
                'false';
        }
    }

    public function prepareGrid()
    {
        $this->setGridModel($this->_activation)
            ->setEagerLoadingModels(['GetOrg', 'UserList', 'PlanList', 'GetUser']);
        return $this;
    }

    public function getGridHeadings()
    {
        return [
            'heading' => [
                ['name' => 'S.No', 'value' => 'SNo', 'sort' => false],
                ['name' => 'Number', 'value' => '', 'sort' => true],
                ['name' => 'Name', 'value' => '', 'sort' => false],
                ['name' => 'Created', 'value' => '', 'sort' => false],
                ['name' => 'Created By', 'value' => '', 'sort' => false],
                ['name' => 'Organization', 'value' => '', 'sort' => false],
                ['name' => 'Report Type', 'value' => '', 'sort' => false],
                ['name' => 'Actions', 'value' => '', 'sort' => false],
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