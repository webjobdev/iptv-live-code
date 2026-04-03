<?php

namespace Contus\StreamServices\Repositories;

use Contus\StreamServices\Model\StreamingUrlPolicy;
use Contus\Base\Helpers\StringLiterals;
use Contus\Base\Repository;
use Illuminate\Support\Facades\Log;

class StreamingUrlPolicyRepository extends Repository
{

    protected $_streamingUrlPolicy;

    public function __construct(StreamingUrlPolicy $streamingUrlPolicy)
    {
        parent::__construct();
        $this->_streamingUrlPolicy = $streamingUrlPolicy;
    }

    public function prepareGrid()
    {
        $this->setGridModel($this->_streamingUrlPolicy)->setEagerLoadingModels('user');
        return $this;
    }

    public function addStreamUrlPolicy()
    {
        Log::info("message", [auth()->user()]);
        $this->setRules([
            'policy_name' => 'required',
            // 'rules' => 'required'
        ]);

        $this->_validate();

        $user = auth()->user();

        $streamUrlPolicy = new StreamingUrlPolicy();
        $streamUrlPolicy->policy_name = $this->request->input('policy_name');

        $rules = $this->request->input('rules');
        $streamUrlPolicy->rules = json_encode($rules);
        $streamUrlPolicy->updated_by = $user->id;
        $streamUrlPolicy->status = 0;
        $streamUrlPolicy->save();

        return response()->json([
            'success' => true,
            'message' => trans('stream-services::index.policy_add.success'),
        ]);
    }

    public function updateStreamUrlPolicy($id)
    {
        $this->setRules([
            'policy_name' => 'required',
            // 'rules' => 'required'
        ]);
        $this->_validate();

        $user = auth()->user();

        $streamUrlPolicy = StreamingUrlPolicy::find($id);
        $streamUrlPolicy->policy_name = $this->request->input('policy_name');

        $rules = $this->request->input('rules');
        $streamUrlPolicy->rules = json_encode($rules);

        $streamUrlPolicy->updated_by = $user->id;
        $streamUrlPolicy->status = '';
        $streamUrlPolicy->save();

        return response()->json([
            'success' => true,
            'message' => trans('stream-services::index.policy_update.success'),
        ]);
    }


    public function statusUpdate()
    {
        $stremingUrlPolicy = StreamingUrlPolicy::where('id', $this->request->input('id'))->update(['status' => $this->request->input('status')]);

        return response()->json([
            'success' => true,
            'message' => trans('stream-services::index.status-update.success'),
        ]);
    }

    public function deleteRecord($id)
    {
        $streamingUrlPolicy = StreamingUrlPolicy::find($id);
        if ($streamingUrlPolicy) {
            $streamingUrlPolicy->delete();

            return response()->json([
                'success' => true,
                'message' => trans('stream-services::index.policy_delete.success'),
            ]);
        }
    }


    public function viewStreamUrlPolicy($id)
    {
        $streamUrlRecord = StreamingUrlPolicy::find($id);
        return response()->json([
            'success' => true,
            'data' => $streamUrlRecord,
            'message' => trans('stream-services::index.fetch-data.success'),
        ]);
    }

    // public function searchByName() {
    //     $stremingUrlPolicy = StreamingUrlPolicy::where('policy_name', 'like', '%' . $this->request->input('name') . '%')->get();

    //     return response()->json([
    //         'success' => true,
    //         'data' => $stremingUrlPolicy,
    //         'message' => trans('stream-services::index.fetch-data.success'),
    //     ]);
    // }

    protected function searchFilter($builderCoupon)
    {
        $searchRecordUsers = $this->request->has(StringLiterals::SEARCHRECORD) && is_array($this->request->input(StringLiterals::SEARCHRECORD)) ? $this->request->input(StringLiterals::SEARCHRECORD) : [];
        foreach ($searchRecordUsers as $key => $value) {
            if ($key == 'status' && $value == 'all') {
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
                ['name' => trans('stream-services::index.name'), 'value' => 'policy_name', 'sort' => true, 'class' => false],
                ['name' => trans('stream-services::index.updated_at'), 'value' => 'updated_at', 'sort' => true, 'class' => false],
                ['name' => trans('stream-services::index.updated_by'), 'value' => 'updated_by', 'sort' => false, 'class' => false],
                ['name' => trans('stream-services::index.content'), 'value' => 'content', 'sort' => false, 'class' => false],
                ['name' => trans('stream-services::index.enable'), 'value' => '', 'sort' => false],
                ['name' => trans('stream-services::index.action'), 'value' => '', 'sort' => false],
            ]
        ];
    }
}
