<?php

namespace Contus\PartnerProgram\Repositories;

use App\Models\User;
use Contus\PartnerProgram\Model\PartnerProgram;
use Contus\Base\Helpers\StringLiterals;
use Contus\Base\Repository;
use Illuminate\Support\Facades\Log;

class PartnerProgramRepository extends Repository
{

    protected $_partnerprogram;

    public function __construct(PartnerProgram $partnerProvider)
    {
        parent::__construct();
        $this->_partnerprogram = $partnerProvider;
    }

    public function prepareGrid()
    {
        $this->setGridModel($this->_partnerprogram)->setEagerLoadingModels('user');
        return $this;
    }

    public function addPartnerProgram()
    {
        $this->setRules([
            'program_name' => 'required',
            'partner_provider' => 'required',
            'partner_code' => 'required',
            'partner_app_logo' => 'required',
            'api_key' => 'required',
            'api_link' => 'required',
            'description' => 'required',
        ]);

        $this->_validate();

        $user = auth()->user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not authenticated'], 401);
        }

        $partnerProgram = new PartnerProgram();
        $partnerProgram->program_name = $this->request->input('program_name');
        $partnerProgram->partner_provider = $this->request->input('partner_provider');
        $partnerProgram->partner_code = $this->request->input('partner_code');
        $partnerProgram->partner_app_logo = $this->request->input('partner_app_logo');
        $partnerProgram->api_key = $this->request->input('api_key');
        $partnerProgram->partner_api_link = $this->request->input('api_link');
        $partnerProgram->description = $this->request->input('description');
        $partnerProgram->created_by = $user->id;
        $partnerProgram->save();
        Log::info('Partner program added successfully', ['partner program' => $partnerProgram]);
        return response()->json([
            'success' => true,
            'message' => trans('partner-programs::index.add.success'),
        ]);
    }

    public function updatePartnerProgram($id)
    {
        $this->setRules([
            'program_name' => 'required',
            'partner_provider' => 'required',
            'partner_code' => 'required',
            'partner_app_logo' => 'required',
            'api_key' => 'required',
            'api_link' => 'required',
            'description' => 'required',
        ]);

        $user = auth()->user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not authenticated'], 401);
        }

        $partnerProgram = PartnerProgram::find($id);
        $partnerProgram->program_name = $this->request->input('program_name');
        $partnerProgram->partner_provider = $this->request->input('partner_provider');
        $partnerProgram->partner_code = $this->request->input('partner_code');
        $partnerProgram->partner_app_logo = $this->request->input('partner_app_logo');
        $partnerProgram->api_key = $this->request->input('api_key');
        $partnerProgram->partner_api_link = $this->request->input('api_link');
        $partnerProgram->description = $this->request->input('description');
        $partnerProgram->created_by = $user->id;
        $partnerProgram->save();

        return response()->json([
            'success' => true,
            'message' => trans('partner-programs::index.update.success'),
        ]);
    }

    public function statusUpdate()
    {
        $partnerProvider = PartnerProgram::where('id', $this->request->input('id'))->update(['status' => $this->request->input('status')]);

        return response()->json([
            'success' => true,
            'message' => trans('partner-programs::index.status-update.success'),
        ]);
    }

    public function searchByName()
    {
        // dd(999);
        $userIds = User::where('name', 'like', '%' . $this->request->input('name') . '%')->pluck('id');
        $partnerProvider = PartnerProgram::with('user')->whereIn('created_by', $userIds)->get();

        return response()->json([
            'success' => true,
            'data' => $partnerProvider,
            'message' => trans('partner-programs::index.fetch-data.success'),
        ]);
    }

    public function recordRemove($id)
    {
        $partnerProgram = PartnerProgram::find($id);
        if ($partnerProgram) {
            $partnerProgram->delete();
            return response()->json([
                'success' => true,
                'message' => trans('partner-programs::index.remove.success'),
            ]);
        } else {
            return response()->json([
                'success' => false
            ]);
        }
    }

    protected function searchFilter($builderCoupon)
    {
        $searchRecords = $this->request->has(StringLiterals::SEARCHRECORD) && is_array($this->request->input(StringLiterals::SEARCHRECORD)) ? $this->request->input(StringLiterals::SEARCHRECORD) : [];
        foreach ($searchRecords as $key => $value) {
            // if ($key == 'status' && $value == 'all') {
            //     continue;
            // }
            $userIds = User::where('name', 'like', '%' . $this->request->input('name') . '%')->pluck('id');
            $partnerProvider = PartnerProgram::with('user')->whereIn('created_by', $userIds)->get();


            if ($key == 'created_by') {
                $builderCoupon = $builderCoupon->whereHas('user', function ($query) use ($value) {
                    $query->where('name', 'like', "%$value%");
                });
                continue;
            }

            // if ($key == 'valid_till') {
            //     $date = date_create($value);
            //     $value =  date_format($date, "Y-m-d");
            // }
            $builderCoupon = $builderCoupon->where($key, 'like', "%$value%");
        }
        return $builderCoupon;
    }

    public function getGridHeadings()
    {
        return [
            'heading' => [
                ['name' => 'S.No', 'S.No' => 'SNo', 'sort' => false],
                ['name' => trans('partner-programs::index.provider'), 'value' => 'partner_provider', 'sort' => true, 'class' => false],
                ['name' => trans('partner-programs::index.pname'), 'value' => 'program_name', 'sort' => true, 'class' => false],
                ['name' => trans('partner-programs::index.created_by'), 'value' => 'user.name', 'sort' => '', 'class' => false],
                ['name' => trans('partner-programs::index.updated_date'), 'value' => 'updated_date', 'sort' => true, 'class' => false],
                // ['name' => trans('partner-programs::index.code'), 'value' => 'partner_code', 'sort' => true, 'class' => false],
                // ['name' => trans('partner-programs::index.api_key'), 'value' => '', 'sort' => false],
                // ['name' => trans('partner-programs::index.api_link'), 'value' => '', 'sort' => false],
                // ['name' => trans('partner-programs::index.desc'), 'value' => '', 'sort' => false],
                ['name' => trans('partner-programs::index.action'), 'value' => '', 'sort' => false],
            ]
        ];
    }
}
