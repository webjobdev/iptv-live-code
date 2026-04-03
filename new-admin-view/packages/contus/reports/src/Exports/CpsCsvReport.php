<?php

namespace Contus\Reports\Exports;

use Carbon\Carbon;
use Contus\Reports\Model\CpsReports;
use Contus\Subscribers\Model\OrgSubscribers;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CpsCsvReport implements FromCollection, WithHeadings
{

    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        if (empty($this->id)) {
            return collect([]);
        }

        // Fetch the report record
        $csv = CpsReports::with('organization')->find($this->id);

        if (!$csv) {
            return collect([]);
        }

        // Fetch selected report types
        $selectedReportType = CpsReports::where('id', $this->id)
            ->whereNotNull('report_type')
            ->pluck('report_type')
            ->unique()
            ->values()
            ->toArray();

        $today = Carbon::today();
        $yesterday = $today->copy()->subDay();
        $minDate = $yesterday->copy()->subDays(365);

        $reportDates = CpsReports::where('id', $this->id)
            ->whereNotNull('report_from_date')
            ->whereNotNull('report_to_date')
            ->get(['report_from_date', 'report_to_date'])
            ->map(function ($item) {
                return [
                    'from' => $item->report_from_date,
                    'to' => $item->report_to_date,
                ];
            })
            ->unique()
            ->values()
            ->toArray();

        $fromDate = !empty($reportDates[0]['from']) ? Carbon::parse($reportDates[0]['from']) : $minDate;
        $toDate = !empty($reportDates[0]['to']) ? Carbon::parse($reportDates[0]['to']) : $yesterday;

        if ($fromDate->lt($minDate))
            $fromDate = $minDate;
        if ($toDate->gt($yesterday))
            $toDate = $yesterday;

        $subscribers = collect();
        $appliedFilters = [];

        if (!empty($selectedReportType)) {
            $query = OrgSubscribers::query()
                ->where('organization_id', $csv->organization)
                ->distinct('id');

            foreach ($selectedReportType as $reportType) {
                switch ($reportType) {
                    case 'active_subscribers':
                        $query->whereHas('subscription_payment_details', function ($q) {
                            $q->where('is_active', 1)
                                // $q->where('is_active', 1)
                                ->where(function ($q2) {
                                    $q2->whereNull('end_date')->orWhere('end_date', '>=', now());
                                });
                            //   ->where(fn($q2) => $q2->whereNull('end_date')->orWhere('end_date', '>=', now()));
                        });
                        $appliedFilters[] = 'Subscriber Status: Active';
                        break;

                    case 'expiring_subscribers':
                        $query->whereHas('subscription_payment_details', function ($q) {
                            $q->where('is_active', 0)->where('end_date', '<=', now());
                        });
                        $appliedFilters[] = 'Subscriber Status: Expiring/Inactive';
                        break;

                    case 'activation_subscribers':
                        $query->whereHas('subscription_payment_details', function ($q) use ($fromDate, $toDate) {
                            $q->whereBetween('start_date', [$fromDate, $toDate])
                                ->whereRaw('org_subscription_and_payments.id = (
                              SELECT MIN(id)
                              FROM org_subscription_and_payments AS spd
                              WHERE spd.subscriber_id = org_subscription_and_payments.subscriber_id
                          )');
                        });
                        $appliedFilters[] = 'Subscriber Status: Activation (First Payment)';
                        break;
                }
            }

            $subscribers = $query->get();
        }

        $cps = [
            // 'record' => $records,
            'report_type' => $selectedReportType,
            'report' => $csv,
            'subscriber' => $subscribers,
            'filter' => $appliedFilters,
        ];

        // dd($cps);

        if ($subscribers->isNotEmpty()) {
            $cpsCollection = $subscribers->map(function ($subscriber) use ($appliedFilters, $csv) {
                return [
                    'Id' => $subscriber->id,
                    'Subscriber Name' => $subscriber->user_name ?? null,
                    'Subscriber Email' => $subscriber->email ?? null,
                    'Subscriber Contact No Code' => $subscriber->phone_number_code ?? null,
                    'Subscriber Contact No' => $subscriber->phone_number ?? null,
                    'Record Applied Filters' => implode(', ', $appliedFilters),
                    // 'Report Id' => $csv->id,
                ];
            });
        } else {
            $cpsCollection = collect([
                [
                    'Id' => null,
                    'Subscriber Name' => 'Subscriber Data Not Fetch',
                    'Subscriber Email' => 'N/A',
                    'Subscriber Contact No Code' => 'N/A',
                    'Subscriber Contact No' => 'N/A',
                    'Record Applied Filters' => implode(', ', $appliedFilters),
                    // 'Report Id' => $csv->id,
                ]
            ]);
        }

        return $cpsCollection;

        // dd($cpsCollection);
    }



    public function headings(): array
    {
        return [
            'Id',
            'Subscriber Name',
            'Subscriber Email',
            'Subscriber Contact No Code',
            'Subscriber Contact No',
            'Record Applied Filters',
        ];
    }
}


// public function collection()
// {
//     \Log::info('CPS CSV export started', ['report_id' => $this->id]);

//     if (empty($this->id)) {
//         \Log::warning('CSV export aborted: Empty report ID provided.');
//         return collect([]);
//     }

//     // Fetch the report record
//     $csv = CpsReports::with('organization')->find($this->id);

//     if (!$csv) {
//         \Log::warning('CSV export aborted: Report not found', ['report_id' => $this->id]);
//         return collect([]);
//     }

//     // Fetching selected fields from CpsReports
//     $selectedReportType = CpsReports::where('id', $this->id)
//         ->whereNotNull('report_type')
//         ->pluck('report_type')
//         ->unique()
//         ->values()
//         ->toArray();

//     $today = Carbon::today();
//     $yesterday = $today->copy()->subDay();
//     $minDate = $yesterday->copy()->subDays(365);

//     $reportDates = CpsReports::where('id', $this->id)
//         ->whereNotNull('report_from_date')
//         ->whereNotNull('report_to_date')
//         ->get(['report_from_date', 'report_to_date'])
//         ->map(function ($item) {
//             return [
//                 'from' => $item->report_from_date,
//                 'to' => $item->report_to_date,
//             ];
//         })
//         ->unique()
//         ->values()
//         ->toArray();

//     $fromDate = !empty($reportDates[0]['from']) ? Carbon::parse($reportDates[0]['from']) : $minDate;
//     $toDate = !empty($reportDates[0]['to']) ? Carbon::parse($reportDates[0]['to']) : $yesterday;

//     if ($fromDate->lt($minDate)) {
//         $fromDate = $minDate;
//     }
//     if ($toDate->gt($yesterday)) {
//         $toDate = $yesterday;
//     }

//     \Log::info('📆 [CPS CSV Export] Date range validated', [
//         'report_id' => $this->id,
//         'from_date' => $fromDate->toDateString(),
//         'to_date' => $toDate->toDateString(),
//         'min_allowed' => $minDate->toDateString(),
//         'max_allowed' => $yesterday->toDateString(),
//     ]);

//     // 🔧 FIXED: You should not filter OrgSubscribers by the report ID — use organization_id
// $query = OrgSubscribers::query()
//     ->where('organization_id', $csv->organization)
//     ->distinct('id');

//     $appliedFilters = [];

//     foreach ($selectedReportType as $reportType) {
//         switch ($reportType) {
//             case 'active_subscribers':
//                 $query->whereHas('subscription_payment_details', function ($q) {
// $q->where('is_active', 1)
//     ->where(function ($q2) {
//         $q2->whereNull('end_date')->orWhere('end_date', '>=', now());
//     });
//                 });
//                 break;

//             case 'expiring_subscribers':
//                 $query->whereHas('subscription_payment_details', function ($q) {
//                     $q->where('is_active', 0)
//                         ->where('end_date', '<=', now());
//                 });
//                 break;

//             case 'activation_subscribers':
//                 $query->whereHas('subscription_payment_details', function ($q) use ($fromDate, $toDate) {
//                     $q->whereBetween('start_date', [$fromDate, $toDate])
//                         ->whereRaw('org_subscription_and_payments.id = (
//           SELECT MIN(id)
//           FROM org_subscription_and_payments AS spd
//           WHERE spd.subscriber_id = org_subscription_and_payments.subscriber_id
//       )');
//                 });
//                 break;

//         }
//     }

//     $subscribers = $query->get();

//     \Log::info('Subscribers fetched', [
//         'organization_id' => $csv->organization,
//         'count' => $subscribers->count(),
//     ]);

//     // Combine data for debugging
//     $cps = [
//         'report_type' => $selectedReportType,
//         'date' => $reportDates,
//         'report' => $csv,
//         'subscriber' => $subscribers,
//         'filter' => $appliedFilters,
//     ];

//     \Log::info('CPS report data prepared', [
//         'report_id' => $this->id,
//         'report_type' => $selectedReportType,
//         'date' => $reportDates,
//         'subscriber_count' => $subscribers->count(),
//     ]);

//     // For debugging, you can temporarily dump it:
//     dd($cps, $subscribers);
// }