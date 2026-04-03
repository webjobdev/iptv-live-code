<?php

namespace Contus\Reports\Exports;

use Contus\Reports\Model\SubscriberReports;
use Contus\Subscribers\Model\OrgSubscribers;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Excel;

class SubscriberTableExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    protected $reportId;

    public function __construct($reportId)
    {
        $this->reportId = $reportId;
    }

    /**
     * Export data collection
     */
    public function collection(): Collection
    {
        if (!$this->reportId) {
            return collect([]);
        }

        $report = SubscriberReports::find($this->reportId);
        if (!$report) {
            return collect([]);
        }

        /** Selected Fields */
        $selectedFields = SubscriberReports::where('id', $this->reportId)
            ->whereNotNull('report_fields')
            ->pluck('report_fields')
            ->toArray();

        /** Selected Filters */
        $selectedFilters = SubscriberReports::where('id', $this->reportId)
            ->whereNotNull('report_filter')
            ->pluck('report_filter')
            ->toArray();

        /** Base Query */
        $query = OrgSubscribers::with('subscription_payment_details')
            ->where('organization_id', $report->organization);

        $appliedFilters = [];

        foreach ($selectedFilters as $filter) {
            switch ($filter) {
                case 'auto_pay':
                    $query->whereHas('subscription_payment_details', function ($q) {
                        $q->where('payment_gateway', 'auto_pay');
                    });
                    $appliedFilters[] = 'Auto Pay Enabled';
                    break;

                case 'subscriber_status':
                    $query->whereHas('subscription_payment_details', function ($q) {
                        $q->where('is_active', 1);
                    });
                    $appliedFilters[] = 'Active Subscribers';
                    break;

                case 'time_period_new_subscribers':
                    $query->whereDate('created_at', '>=', now()->subDays(7));
                    $appliedFilters[] = 'Last 7 Days';
                    break;

                case 'payment_status':
                    $query->whereHas('subscription_payment_details', function ($q) {
                        $q->where('payment_status', 'PAYMENT_SUCCESS');
                    });
                    $appliedFilters[] = 'Payment Success';
                    break;
            }
        }

        $subscribers = $query->get();

        /**
         * Pivot-Ready Rows
         */
        return $subscribers->map(function ($subscriber) use ($report, $appliedFilters) {
            return [
                'Report Id' => $report->id,
                'Report Name' => $report->report_name,
                'Report Type' => $report->report_type,
                'Subscriber Id' => $subscriber->id,
                'First Name' => $subscriber->first_name,
                'Last Name' => $subscriber->last_name,
                'Email' => $subscriber->email,
                'Country' => $subscriber->country,
                'State' => $subscriber->state,
                'City' => $subscriber->city,
                'Subscriber Status' => $subscriber->subscriber_status,
                'Auto Pay' => $subscriber->auto_pay ? 'Yes' : 'No',
                'Created Date' => optional($subscriber->created_at)->format('Y-m-d'),
                'Applied Filters' => implode(', ', $appliedFilters),
            ];
        });
    }

    /**
     * Excel headings
     */
    public function headings(): array
    {
        return [
            'Report Id',
            'Report Name',
            'Report Type',
            'Subscriber Id',
            'First Name',
            'Last Name',
            'Email',
            'Country',
            'State',
            'City',
            'Subscriber Status',
            'Auto Pay',
            'Created Date',
            'Applied Filters',
        ];
    }
}
