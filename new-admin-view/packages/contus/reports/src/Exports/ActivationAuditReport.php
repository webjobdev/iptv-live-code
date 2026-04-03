<?php

namespace Contus\Reports\Exports;

use Carbon\Carbon;
use Contus\Reports\Model\Activation;
use Contus\Subscribers\Model\OrgSubscribers;
use Maatwebsite\Excel\Concerns\FromCollection;

class ActivationAuditReport implements FromCollection
{
    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * @return \Illuminate\Support\Collection
     */

    // public function collection()
    // {
    //     \Log::info('csv called', ['report_id' => $this->id]);

    //     if (empty($this->id)) {
    //         \Log::warning('csv: Empty ID provided');
    //         return collect([]);
    //     }

    //     // Fetch report with relation
    //     $csvreport = Activation::with('GetOrg')->find($this->id);
    //     \Log::info('Report fetched', ['report_id' => $this->id, 'organization_id' => $csvreport->organization]);

    //     if (!$csvreport) {
    //         return collect([]);
    //     }

    //     $selectedFields = Activation::where('id', $this->id)
    //         ->whereNotNull('payment_service')
    //         ->where('payment_service', '!=', '')
    //         ->pluck('payment_service')
    //         ->unique()
    //         ->values()
    //         ->toArray();
    //     \Log::info('Selected fields', ['fields' => $selectedFields]);

    //     $selectedFilters = Activation::where('id', $this->id)
    //         ->whereNotNull('autopay')
    //         ->where('autopay', '!=', '')
    //         ->pluck('autopay')
    //         ->unique()
    //         ->values()
    //         ->toArray();
    //     \Log::info('Selected filters', ['filters' => $selectedFilters]);

    //     $reportDates = Activation::where('id', $this->id)
    //         ->whereNotNull('subscription_length_from_date')
    //         ->whereNotNull('subscription_length_to_date')
    //         ->get(['subscription_length_from_date', 'subscription_length_to_date'])
    //         ->map(function ($item) {
    //             return [
    //                 'from' => $item->subscription_length_from_date,
    //                 'to' => $item->subscription_length_to_date,
    //             ];
    //         })
    //         ->unique()
    //         ->values()
    //         ->toArray();
    //     \Log::info('Selected filters', ['Report Date' => $reportDates]);

    //     // Fetch subscribers from that organization
    //     $query = OrgSubscribers::with(['subscription_payment_details', 'subscription_payment_details.TransactionDetail'])
    //         ->where('organization_id', $csvreport->organization)
    //         ->distinct('id');

    //     // \Log::info('Subscribers fetched', ['count' => $query->count()]);

    //     // dd($query);

    //     $appliedFilters = [];
    //     foreach ($selectedFields as $filter) {
    //         switch ($filter) {
    //             case 'authorize.net':
    //                 // Filter subscribers whose transactions were processed through Authorize.Net
    //                 $query->whereHas('subscription_payment_details.TransactionDetail', function ($q) {
    //                     $q->where('payment_gateway', 'authorize.net');
    //                 });
    //                 $appliedFilters[] = 'Payment System: Authorize.Net';
    //                 \Log::info('Applied filter', ['Payment System filter type' => 'authorize_net']);
    //                 break;

    //             case 'cash':
    //                 // Filter subscribers who paid by cash
    //                 $query->whereHas('subscription_payment_details.TransactionDetail', function ($q) {
    //                     $q->where('payment_gateway', 'cash');
    //                 });
    //                 $appliedFilters[] = 'Payment System: Cash';
    //                 \Log::info('Applied filter', ['Payment System filter type' => 'cash']);
    //                 break;

    //             case 'check':
    //                 // Filter subscribers who paid by check
    //                 $query->whereHas('subscription_payment_details.TransactionDetail', function ($q) {
    //                     $q->where('payment_gateway', 'check');
    //                 });
    //                 $appliedFilters[] = 'Payment System: Check';
    //                 \Log::info('Applied filter', ['Payment System filter type' => 'check']);
    //                 break;

    //             case 'external_payment':
    //                 // Filter successful external payments (e.g., Razorpay, Stripe, etc.)
    //                 $query->whereHas('subscription_payment_details.TransactionDetail', function ($q) {
    //                     $q->where('payment_gateway', 'external_payment');
    //                 });
    //                 $appliedFilters[] = 'Payment System: External Payment (Successful)';
    //                 \Log::info('Applied filter', ['Payment System filter type' => 'external_payment']);
    //                 break;

    //             default:
    //                 \Log::warning('No filter matched', ['filter_value' => $filter]);
    //                 break;
    //         }
    //     }

    //     $subscriber = $query->get();
    //     \Log::info('Subscribers fetched', ['count' => $subscriber->count()]);


    //     $records = $subscriber->map(function ($subscriber) use ($selectedFields, $appliedFilters) {
    //         $row = [];

    //         // Map selected fields
    //         foreach ($selectedFields as $field) {
    //             switch ($field) {
    //                 case 'authorize.net':
    //                     $row['authorize.net'] = $subscriber->authorize_net;
    //                     break;

    //                 case 'cash':
    //                     $row['cash'] = $subscriber->cash;
    //                     break;

    //                 case 'check':
    //                     $row['check'] = $subscriber->check;
    //                     break;

    //                 case 'external_payment':
    //                     $row['external_payment'] = $subscriber->external_payment;
    //                     break;

    //                 default:
    //                     $row[$field] = data_get($subscriber, $field);
    //                     break;
    //             }
    //         }

    //         // Add applied filters to the row
    //         $row['applied_filters'] = implode(', ', $appliedFilters);

    //         return $row;
    //     });

    //     \Log::info('Records mapped for csv', ['records_count' => $records->count()]);


    //     $csv = [
    //         'record' => $records,
    //         'fields' => $selectedFields,
    //         // 'filters' => $appliedFilters,
    //         'report' => $csvreport,
    //     ];

    //     \Log::info('CSV data prepared', [
    //         'record_count' => $records->count(),
    //         'records' => $records->toArray(),
    //         'fields' => $selectedFields,
    //         'report_id' => $csvreport->id,
    //         'organization_id' => $csvreport->organization,
    //     ]);


    //     dd($csv);

    //     // $csvCollection = collect($csv['record'])->map(function ($record) use ($csvreport) {
    //     //     return [
    //     //         'Id' => $csvreport['id'] ?? null,
    //     //         'Report Name' => $csvreport['report_name'] ?? null,
    //     //         'Report Type' => $csvreport['report_type'] ?? null,
    //     //         'Address' => $record['address'] ?? null,
    //     //         'Applied Filters' => $record['applied_filters'] ?? null,
    //     //     ];
    //     // });

    //     // return $csvCollection;
    // }

    public function collection()
    {
        \Log::info('CSV export started', ['report_id' => $this->id]);

        if (empty($this->id)) {
            \Log::warning('CSV export: Empty ID provided');
            return collect([]);
        }

        // Fetch report with organization
        $csvreport = Activation::with('GetOrg')->find($this->id);

        if (!$csvreport) {
            \Log::warning('CSV export: Report not found', ['report_id' => $this->id]);
            return collect([]);
        }

        \Log::info('Report fetched', [
            'report_id' => $this->id,
            'organization_id' => $csvreport->organization
        ]);

        // Get report date range
        $reportDates = Activation::where('id', $this->id)
            ->whereNotNull('subscription_length_from_date')
            ->whereNotNull('subscription_length_to_date')
            ->get(['subscription_length_from_date', 'subscription_length_to_date'])
            ->map(function ($item) {
                return [
                    'from' => $item->subscription_length_from_date,
                    'to' => $item->subscription_length_to_date,
                ];
            })
            ->unique()
            ->values()
            ->toArray();

        $fromDate = !empty($reportDates[0]['from']) ? Carbon::parse($reportDates[0]['from'])->startOfDay() : Carbon::parse('2000-01-01');
        $toDate = !empty($reportDates[0]['to']) ? Carbon::parse($reportDates[0]['to'])->endOfDay() : Carbon::now()->endOfDay();

        \Log::info('Report date range', ['from' => $fromDate, 'to' => $toDate]);

        // Get selected payment fields
        $selectedFields = Activation::where('id', $this->id)
            ->whereNotNull('payment_service')
            ->pluck('payment_service')
            ->unique()
            ->values()
            ->toArray();

        \Log::info('Selected fields', ['fields' => $selectedFields]);

        // Base subscriber query with eager loading of nested relationship
        $query = OrgSubscribers::query()
            ->where('organization_id', $csvreport->organization)
            ->distinct();

        $appliedFilters = [];

        // Apply payment filters
        foreach ($selectedFields as $filter) {
            \Log::info('Applying filter', ['filter_value' => $filter]);

            switch (strtolower($filter)) {
                case 'authorize.net':
                    $query->whereHas('subscription_payment_details', function ($q) {
                        $q->where('payment_service', 'authorize.net');
                        // ->whereBetween('created_at', [$fromDate, $toDate]);
                    });
                    $appliedFilters[] = 'Payment System: Authorize.Net';
                    break;

                case 'cash':
                    $query->whereHas('subscription_payment_details', function ($q) {
                        $q->where('payment_service', 'cash');
                        // ->whereBetween('created_at', [$fromDate, $toDate]);
                    });
                    $appliedFilters[] = 'Payment System: Cash';
                    break;

                case 'check':
                    $query->whereHas('subscription_payment_details', function ($q) {
                        $q->where('payment_service', 'check');
                        // ->whereBetween('created_at', [$fromDate, $toDate]);
                    });
                    $appliedFilters[] = 'Payment System: Check';
                    break;

                case 'external_payment':
                    $query->whereHas('subscription_payment_details', function ($q) {
                        $q->where('payment_status', 'PAYMENT_SUCCESS');
                        // ->whereNotNull('payment_service');
                        // ->whereBetween('created_at', [$fromDate, $toDate]);
                    });
                    $appliedFilters[] = 'Payment System: External Payment (Successful)';
                    break;

                default:
                    \Log::warning('No filter matched', ['filter_value' => $filter]);
                    break;
            }
        }

        // Fetch subscribers
        $subscribers = $query->get();

        \Log::info('Subscribers fetched', [
            'count' => $subscribers->count(),
            'applied_filters' => $appliedFilters
        ]);

        // dd($subscribers);

        // Map subscribers to CSV
        $records = $subscribers->map(function ($subscriber) use ($selectedFields, $appliedFilters) {
            $row = [];

            foreach ($selectedFields as $field) {
                $fieldLower = strtolower($field);

                switch ($fieldLower) {
                    case 'authorize.net':
                        $row['authorize.net'] = $subscriber->authorize.net;
                        break;

                    case 'cash':
                        $row['cash'] = $subscriber->cash;
                        break;

                    case 'check':
                        $row['check'] = $subscriber->check;
                        break;

                    case 'external_payment':
                        $row['external_payment'] = $subscriber->external_payment;
                        break;

                    default:
                        $row[$field] = data_get($subscriber, $field);
                        break;
                }
            }

            $row['applied_filters'] = implode(', ', $appliedFilters);

            return $row;
        });


        // \Log::info('Records mapped for CSV', ['records_count' => $records->count()]);

        // dd($records);

        if ($subscribers->isNotEmpty()) {
            $cpsCollection = $subscribers->map(function ($subscriber) use ($appliedFilters) {
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

        // dd($cpsCollection);
        return $cpsCollection;
    }


    public function headings(): array
    {
        return [
            'Id',
            'Report Name',
            'Report Type',
            'Address',
            'Applied Filters'
        ];
    }

}
