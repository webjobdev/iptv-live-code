<?php

namespace Contus\ApiAccess\Repositories;

use Contus\ApiAccess\Model\ApiAccess;
use Contus\Base\Helpers\StringLiterals;
use Contus\Base\Repository;
use Illuminate\Support\Facades\Log;

class ApiAccessRepository extends Repository
{

    protected $_apiaccess;

    public function __construct(ApiAccess $apiaccess)
    {
        parent::__construct();
        $this->_apiaccess = $apiaccess;
    }

    public function prepareGrid()
    {
        $this->setGridModel($this->_apiaccess)->setEagerLoadingModels(['organization', 'organization.monPlan']);
        return $this;
    }

    public function addApiUser()
    {
        $this->setRules([
            'name' => 'required',
            'login' => 'required',
            'token' => 'required',
            'organization' => 'required',
            'subscription' => 'nullable',
        ]);

        $this->_validate();

        $apiUser = new ApiAccess();
        $apiUser->name = $this->request->input('name');
        $apiUser->login = $this->request->input('login');
        $apiUser->token = $this->request->input('token');
        $apiUser->organization_id = $this->request->input('organization')['id'];
        $apiUser->subscription_id = $this->request->input('subscription')['id'];
        $apiUser->add_on = $this->request->input('addon');
        $apiUser->save();

        return response()->json([
            'success' => true,
            'message' => trans('api-access::index.add.success'),
        ]);
    }

    public function updateApiUser($id)
    {
        $this->setRules([
            'name' => 'required',
            'login' => 'required',
            'token' => 'required',
            'organization_id' => 'required',
            'subscription' => 'nullable',
        ]);

        $this->_validate();

        $apiUser = ApiAccess::find($id);
        $apiUser->name = $this->request->input('name');
        $apiUser->login = $this->request->input('login');
        $apiUser->token = $this->request->input('token');
        $apiUser->organization_id = $this->request->input('organization_id');
        $apiUser->subscription_id = $this->request->input('subscription_id');
        $apiUser->add_on = $this->request->input('add_on');
        $apiUser->save();

        return response()->json([
            'success' => true,
            'message' => trans('api-access::index.update.success'),
        ]);
    }

    public function statusUpdate()
    {
        $apiAccess = ApiAccess::where('id', $this->request->input('id'))->update(['status' => $this->request->input('status')]);

        return response()->json([
            'success' => true,
            'message' => trans('api-access::index.status-update.success'),
        ]);
    }

    public function removeApiAccess($id)
    {
        $apiAccess = ApiAccess::find($id);
        if ($apiAccess) {
            $apiAccess->delete();
            return response()->json([
                'success' => true,
                'message' => trans('api-access::index.remove.success'),
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => trans('api-access::index.remove.error'),
            ]);
        }
    }

    protected function searchFilter($builderCoupon)
    {
        $searchRecordUsers = $this->request->has(StringLiterals::SEARCHRECORD) && is_array($this->request->input(StringLiterals::SEARCHRECORD)) ? $this->request->input(StringLiterals::SEARCHRECORD) : [];
        foreach ($searchRecordUsers as $key => $value) {
            if ($key == 'status' && $value == 'all') {
                continue;
            }

            if ($key == 'organization') {
                $builderCoupon = $builderCoupon->whereHas('organization', function ($query) use ($value) {
                    $query->where('organization_name', 'like', "%$value%");
                });
                continue;
            }

            if ($key == 'valid_till') {
                $date = date_create($value);
                $value = date_format($date, "Y-m-d");
            }
            $builderCoupon = $builderCoupon->where($key, 'like', "%$value%");
        }
        return $builderCoupon;
    }

    public function getGridHeadings()
    {
        return [
            'heading' => [
                ['name' => 'S.No', 'S.No' => 'SNo', 'sort' => false],
                ['name' => trans('api-access::index.login'), 'value' => 'login', 'sort' => true, 'class' => false],
                ['name' => trans('api-access::index.name'), 'value' => 'name', 'sort' => true, 'class' => false],
                ['name' => trans('api-access::index.organization_id'), 'value' => 'organization_id', 'sort' => true, 'class' => false],
                // ['name' => trans('api-access::index.token'), 'value' => '', 'sort' => false],
                // ['name' => trans('api-access::index.status'), 'value' => '', 'sort' => false],
                ['name' => trans('api-access::index.action'), 'value' => '', 'sort' => false],
            ]
        ];
    }
}
