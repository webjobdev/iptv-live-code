<?php

namespace Contus\StreamServices\Repositories;

use Contus\StreamServices\Model\StreamSetting;
use Contus\Base\Helpers\StringLiterals;
use Contus\Base\Repository;
use Illuminate\Support\Facades\Log;

class StreamSettingRepository extends Repository {

    protected $_streamService;

    public function __construct(StreamSetting $streamService) {
        parent::__construct();
        $this->_streamService = $streamService;
    }

    public function prepareGrid() {
        $this->setGridModel($this->_streamService);
        return $this;
    }

    public function addStreamSettings() {
        $this->setRules([
            'name' => 'required',
            'login' => 'required',
            'token' => 'required',
            'organization' => 'required',
            'subscription' => 'required',
        ]);

        $apiUser = new StreamSetting();
        $apiUser->name = $this->request->input('name');
        $apiUser->login = $this->request->input('login');
        $apiUser->token = $this->request->input('token');
        $apiUser->organization_id = $this->request->input('organization')['id'];
        $apiUser->subscription_id = $this->request->input('subscription')['id'];
        $apiUser->add_on = $this->request->input('addon');
        $apiUser->save();

        return response()->json([
            'success' => true,
            'message' => trans('api-access::index.policy_add.success'),
        ]);
    }

    public function updateStreamSettings($id) {
        $this->setRules([
            'name' => 'required',
            'login' => 'required',
            'token' => 'required',
            'organization' => 'required',
            'subscription' => 'required',
        ]);

        $apiUser = StreamSetting::find($id);
        $apiUser->name = $this->request->input('name');
        $apiUser->login = $this->request->input('login');
        $apiUser->token = $this->request->input('token');
        $apiUser->organization_id = $this->request->input('organization')['id'];
        $apiUser->subscription_id = $this->request->input('subscription')['id'];
        $apiUser->add_on = $this->request->input('addon');
        $apiUser->save();

        return response()->json([
            'success' => true,
            'message' => trans('api-access::index.policy_update.success'),
        ]);
    }

    public function statusUpdate() {
        $apiAccess = StreamSetting::where('id', $this->request->input('id'))->update(['status' => $this->request->input('status')]);

        return response()->json([
            'success' => true,
            'message' => trans('api-access::index.status-update.success'),
        ]);
    }

    public function searchByName() {
        $apiAccess = StreamSetting::where('name', 'like', '%' . $this->request->input('name') . '%')->get();

        return response()->json([
            'success' => true,
            'data' => $apiAccess,
            'message' => trans('api-access::index.fetch-data.success'),
        ]);
    }

    protected function searchFilter($builderCoupon) {
        $searchRecordUsers = $this->request->has(StringLiterals::SEARCHRECORD) && is_array($this->request->input(StringLiterals::SEARCHRECORD)) ? $this->request->input(StringLiterals::SEARCHRECORD) : [];
        foreach ($searchRecordUsers as $key => $value) {
            if ($key == 'status' && $value == 'all') {
                continue;
            }

            if ($key == 'valid_till') {
                $date = date_create($value);
                $value =  date_format($date, "Y-m-d");
            }
            $builderCoupon = $builderCoupon->where($key, 'like', "%$value%");
        }
        return $builderCoupon;
    }

    public function getGridHeadings() {
        return ['heading' => [
            ['name' => 'S.No', 'S.No' => 'SNo', 'sort' => false],
            // ['name' => trans('stream-services::index.name'), 'value' => 'name', 'sort' => true, 'class' => false],
            // ['name' => trans('stream-services::index.updated_at'), 'value' => 'updated_at', 'sort' => true, 'class' => false],
            // ['name' => trans('stream-services::index.updated_by'), 'value' => 'updated_by', 'sort' => false, 'class' => false],
            // ['name' => trans('stream-services::index.content'), 'value' => 'content', 'sort' => false, 'class' => false],
            // ['name' => trans('stream-services::index.action'), 'value' => '', 'sort' => false],
            ['name' => 'Name', 'value' => 'name', 'sort' => true, 'class' => false],
            ['name' => 'Node', 'value' => 'node', 'sort' => false, 'class' => false],
            ['name' => 'Preset', 'value' => 'preset', 'sort' => false, 'class' => false],
            ['name' => 'Status', 'value' => 'status', 'sort' => false, 'class' => false],
            ['name' => 'Input/Rate', 'value' => '', 'sort' => false, 'class' => false],
            ['name' => 'Started', 'value' => '', 'sort' => false, 'class' => false],
            ['name' => 'Last Reset', 'value' => '', 'sort' => false, 'class' => false],
            ['name' => 'Restart', 'value' => '', 'sort' => false],
            ['name' => 'CPU', 'value' => '', 'sort' => false],
            ['name' => 'RSS', 'value' => '', 'sort' => false],
            ['name' => 'Created By', 'value' => '', 'sort' => false],
            ['name' => 'Status', 'value' => '', 'sort' => false],
            ['name' => 'Action', 'value' => '', 'sort' => false],
        ]];
    }
}
