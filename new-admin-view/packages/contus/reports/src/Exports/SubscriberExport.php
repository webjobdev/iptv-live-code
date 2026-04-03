<?php

namespace Contus\Reports\Exports;

use Carbon\Carbon;
use Contus\Reports\Model\SubscriberReports;
use Contus\Subscribers\Model\OrgSubscribers;
use Log;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SubscriberExport implements FromCollection, WithHeadings
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
        // \Log::info('csv called', ['report_id' => $this->id]);

        if (empty($this->id)) {
            // \Log::warning('csv: Empty ID provided');
            return collect([]);
        }

        // Fetch report with relation
        $csvreport = SubscriberReports::with('GetOrganization')->find($this->id);
        // \Log::info('Report fetched', ['report_id' => $this->id, 'organization_id' => $csvreport->organization]);

        if (!$csvreport) {
            return collect([]);
        }

        $selectedFields = SubscriberReports::where('id', $this->id)
            ->whereNotNull('report_fields')
            ->where('report_fields', '!=', '')
            ->pluck('report_fields')
            ->unique()
            ->values()
            ->toArray();
        // \Log::info('Selected fields', ['fields' => $selectedFields]);

        $selectedFilters = SubscriberReports::where('id', $this->id)
            ->whereNotNull('report_filter')
            ->where('report_filter', '!=', '')
            ->pluck('report_filter')
            ->unique()
            ->values()
            ->toArray();
        // \Log::info('Selected filters', ['filters' => $selectedFilters]);

        // Fetch subscribers from that organization
        $query = OrgSubscribers::with(['subscription_payment_details'])
            ->where('organization_id', $csvreport->organization)
            ->distinct('id');
        // ->get();

        $appliedFilters = [];

        foreach ($selectedFilters as $filter) {
            switch ($filter) {
                case 'auto_pay':
                    $query->whereHas('subscription_payment_details', function ($q) {
                        $q->whereIn('product_type', ['subscription sets', 'custom subscription', 'free subscription'])
                            ->where('payment_gateway', 'auto_pay');
                    });
                    // $query->where('auto_pay', 1);
                    $appliedFilters[] = 'Auto Pay: Enabled';
                    // \Log::info('Applied filter', ['filter' => 'auto_pay']);
                    break;

                case 'subscriber_status':
                    $query->whereHas('subscription_payment_details', function ($q) {
                        $q->whereIn('product_type', ['subscription sets', 'custom subscription', 'free subscription'])
                            ->where('is_active', 1);
                    });
                    $appliedFilters[] = 'Subscriber Status: Active';

                    // \Log::info('Applied filter', ['filter' => 'subscriber_status']);
                    break;

                case 'time_period_new_subscribers':
                    $query->whereDate('created_at', '>=', now()->subDays(7));
                    $appliedFilters[] = 'New Subscribers: Last 7 Days';
                    // \Log::info('Applied filter', ['filter' => 'time_period_new_subscribers']);
                    break;

                case 'payment_status':
                    $query->whereHas('subscription_payment_details', function ($q) {
                        $q->where('payment_status', 'PAYMENT_SUCCESS')
                            ->addSelect('payment_service');
                    });
                    $appliedFilters[] = 'Payment Status: Success';
                    // \Log::info('Applied filter', ['filter' => 'payment_status']);
                    break;
            }
        }

        $subscriber = $query->get();
        // \Log::info('Subscribers fetched', ['count' => $query->count()]);

        $records = $subscriber->map(function ($subscriber) use ($selectedFields, $appliedFilters) {
            $row = [];

            // Map selected fields
            foreach ($selectedFields as $field) {
                switch ($field) {
                    case 'address':
                        $row['address'] = $subscriber->address;
                        break;

                    case 'city':
                        $row['city'] = $subscriber->city;
                        break;

                    case 'country':
                        $row['country'] = $subscriber->country;
                        break;

                    case 'email':
                        $row['email'] = $subscriber->email;
                        break;

                    case 'expiration_time':
                        $row['expiration_time'] = $subscriber->expiration_time;
                        break;

                    case 'first_name':
                        $row['first_name'] = $subscriber->first_name;
                        break;

                    case 'last_access_time':
                        $row['last_access_time'] = $subscriber->last_access_time;
                        break;

                    case 'last_name':
                        $row['last_name'] = $subscriber->last_name;
                        break;

                    case 'phone':
                        $row['phone'] = $subscriber->phone;
                        break;

                    case 'state':
                        $row['state'] = $subscriber->state;
                        break;

                    case 'zip_code':
                        $row['zip_code'] = $subscriber->zip_code;
                        break;

                    case 'subscriber_status':
                        $row['subscriber_status'] = $subscriber->subscriber_status;
                        break;

                    case 'auto_pay':
                        $row['auto_pay'] = $subscriber->auto_pay;
                        break;

                    case 'time_period_new_subscribers':
                        $row['time_period_new_subscribers'] = $subscriber->time_period_new_subscribers;
                        break;

                    case 'payment_status':
                        $row['payment_status'] = $subscriber->payment_status;
                        break;

                    default:
                        $row[$field] = data_get($subscriber, $field);
                        break;
                }
            }

            // Add applied filters to the row
            $row['applied_filters'] = implode(', ', $appliedFilters);

            return $row;
        });


        // \Log::info('Records mapped for csv', ['records_count' => $records->count()]);


        $csv = [
            'record' => $records,
            'fields' => $selectedFields,
            'filters' => $appliedFilters,
            'report' => $csvreport,
        ];

        // \Log::info('CSV data prepared', [
        //     'record_count' => $records->count(),
        //     'records' => $records->toArray(),
        //     'fields' => $selectedFields,
        //     'filters' => $appliedFilters,
        //     'report_id' => $csvreport->id,
        //     'organization_id' => $csvreport->organization,
        // ]);


        // dd($csv);

        $csvCollection = collect($csv['record'])->map(function ($record) use ($csvreport) {
            return [
                'Id' => $csvreport['id'] ?? null,
                'Report Name' => $csvreport['report_name'] ?? null,
                'Report Type' => $csvreport['report_type'] ?? null,
                'Address' => $record['address'] ?? null,
                'Applied Filters' => $record['applied_filters'] ?? null,
            ];
        });

        return $csvCollection;
    }

    /**
     * Define CSV headings
     */
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
