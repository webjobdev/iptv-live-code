<?php

namespace Contus\Reports\Repositories;

use Carbon\Carbon;
use Contus\Base\Helpers\StringLiterals;
use Contus\Base\Repository;
use Contus\Organizations\Model\OrgSubscribers;
use Contus\Reports\Model\CpsReports;
use Contus\User\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CpsReportRepository extends Repository
{
    protected $_cpsReports;
    public function __construct(CpsReports $cpsReports)
    {
        parent::__construct();
        $this->_cpsReports = $cpsReports;
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
            'report_type' => 'required',
            'organization' => 'required',
            // 'report_from_date' => 'required',
            // 'report_to_date' => 'required',
            // 'generate' => 'required'
        ]);

        $this->_validate();

        $insert = new CpsReports();

        $insert->report_name = $request['report_name'];
        $insert->report_type = $request['report_type'];
        $insert->organization = $request['organization'];
        $insert->report_from_date = $request['report_from_date'];
        $insert->report_to_date = $request['report_to_date'];
        $insert->generate = $request['generate'];
        $insert->created_by = $user->id;

        $insert->save();

        return 'success';
    }


    public function GetCpsData()
    {
        Log::info('GetCpsData() called.');

        // Fetch all reports with their organizations
        $reports = CpsReports::with('organization')
            ->whereNotNull('report_type')
            ->get();

        if ($reports->isEmpty()) {
            Log::warning('No reports found with report_type.');
            return response()->json([
                'chartData' => ['labels' => [], 'data' => []],
            ]);
        }

        // Collect all report types
        $selectedReportTypes = $reports->pluck('report_type')
            ->flatten()
            ->unique()
            ->values()
            ->toArray();

        Log::info('Selected Report Types:', $selectedReportTypes);

        $chartData = ['labels' => [], 'data' => []];

        if (!empty($selectedReportTypes)) {
            foreach ($selectedReportTypes as $reportType) {
                Log::info('Processing report type: ' . $reportType);

                switch ($reportType) {
                    // ✅ Active subscribers
                    case 'active_subscribers':
                        $count = OrgSubscribers::whereHas('subscription_payment_detail', function ($q) {
                            $q->where('is_active', 1)
                                ->where(function ($q2) {
                                    $q2->whereNull('end_date')->orWhere('end_date', '>=', now());
                                });
                        })->count();

                        Log::info('Active Subscribers count: ' . $count);

                        $chartData['labels'][] = 'Active Subscribers';
                        $chartData['data'][] = $count;
                        break;

                    // ✅ Inactive subscribers
                    case 'inactive_subscribers':
                    case 'expiring_subscribers': // treat expiring and inactive as same
                        $count = OrgSubscribers::whereHas('subscription_payment_detail', function ($q) {
                            $q->where('is_active', 0)
                                ->where('end_date', '<=', now());
                        })->count();

                        Log::info('Inactive Subscribers count: ' . $count);

                        $chartData['labels'][] = 'Inactive/Expiring Subscribers';
                        $chartData['data'][] = $count;
                        break;

                    // ✅ Activation (first-time subscribers)
                    case 'activation_subscribers':
                        $count = OrgSubscribers::whereHas('subscription_payment_detail', function ($q) {
                            $q->whereRaw('org_subscription_and_payments.id = (
                            SELECT MIN(id)
                            FROM org_subscription_and_payments AS spd
                            WHERE spd.subscriber_id = org_subscription_and_payments.subscriber_id
                        )');
                        })->count();

                        Log::info('Activation Subscribers count: ' . $count);

                        $chartData['labels'][] = 'Activation Subscribers';
                        $chartData['data'][] = $count;
                        break;

                    default:
                        Log::warning('Unknown report type encountered: ' . $reportType);
                        break;
                }
            }
        } else {
            Log::info('No report types found.');
        }

        Log::info('Final Chart Data:', $chartData);

        return [
            'chartData' => $chartData,
        ];
    }


    public function prepareGrid()
    {
        $this->setGridModel($this->_cpsReports)
            ->setEagerLoadingModels(['organization', 'GetUser']);
        return $this;
    }

    public function getGridHeadings()
    {
        return [
            'heading' => [
                ['name' => 'S.No', 'value' => 'SNo', 'sort' => false],
                ['name' => 'Id', 'value' => '', 'sort' => true],
                ['name' => 'Name', 'value' => '', 'sort' => false],
                ['name' => 'Status', 'value' => '', 'sort' => false],
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