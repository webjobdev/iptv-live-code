<?php

namespace Contus\Reports\Http\Controllers\Admin;

use Barryvdh\DomPDF\Facade\Pdf;
use Contus\Base\Controller;
use Contus\Reports\Exports\CpsCsvReports;
use Contus\Reports\Exports\SubscriberExport;
use Contus\Reports\Exports\SubscriberTableExport;
use Contus\Subscribers\Model\OrgSubscribers;
use Illuminate\Support\Facades\Log;
use Contus\Reports\Model\SubscriberReports;
use Maatwebsite\Excel\Facades\Excel;

final class ReportsIndexController extends Controller
{
    // ==========***********==========
    public function subscriberIndex()
    {
        return view('reports::subscriber-reports.index');
    }
    public function saveTemplateIndex()
    {
        return view('reports::subscriber-reports.save-reports.index');
    }
    public function templatesGridList()
    {
        return view('reports::subscriber-reports.save-reports.gridView');
    }
    public function generateIndex()
    {
        return view('reports::subscriber-reports.generate-reports.index');
    }
    public function generateGridList()
    {
        return view('reports::subscriber-reports.generate-reports.gridView');
    }
    // ==========***********==========


    // ==========***********==========
    public function cpsIndex()
    {
        return view('reports::cps-reports.index');
    }
    public function saveIndex()
    {
        return view('reports::cps-reports.generate-reports.index');
    }
    public function cpsGridList()
    {
        return view('reports::cps-reports.generate-reports.gridView');
    }
    // ==========***********==========


    // ==========***********==========
    public function activationIndex()
    {
        return view('reports::activation-reports.index');
    }
    public function TemplateIndex()
    {
        return view('reports::activation-reports.save-reports.index');
    }
    public function activationGridList()
    {
        return view('reports::activation-reports.save-reports.gridView');
    }
    public function actvationIndex()
    {
        return view('reports::activation-reports.generate-reports.index');
    }
    public function actvationGridList()
    {
        return view('reports::activation-reports.generate-reports.gridView');
    }
    // ==========***********==========

    // ==========***********==========

    public function downloadPdf($id)
    {
        // \Log::info('downloadPdf called', ['report_id' => $id]);

        if (empty($id)) {
            \Log::warning('downloadPdf: Empty ID provided');
            return 'false';
        }

        // 1) Fetch the report definition
        $report = SubscriberReports::with('GetOrganization')->findOrFail($id);
        // \Log::info('Report fetched', ['report_id' => $id, 'organization_id' => $report->organization]);

        $selectedFields = SubscriberReports::where('id', $id)
            ->whereNotNull('report_fields')
            ->where('report_fields', '!=', '')
            ->pluck('report_fields')
            ->unique()
            ->values()
            ->toArray();
        // \Log::info('Selected fields', ['fields' => $selectedFields]);

        $selectedFilters = SubscriberReports::where('id', $id)
            ->whereNotNull('report_filter')
            ->where('report_filter', '!=', '')
            ->pluck('report_filter')
            ->unique()
            ->values()
            ->toArray();
        // \Log::info('Selected filters', ['filters' => $selectedFilters]);

        // 2) Build base query
        $query = OrgSubscribers::with('subscription_payment_details')
            ->where('organization_id', $report->organization);
        // \Log::info('Base subscriber query built', ['sql' => $query->toSql(), 'bindings' => $query->getBindings()]);

        $appliedFilters = [];

        // 3) Apply filters dynamically
        foreach ($selectedFilters as $filter) {
            switch ($filter) {
                case 'auto_pay':
                    $query->where('auto_pay', 1);
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
                    $query->whereDate('created_at', '>=', now()->subDays(30));
                    $appliedFilters[] = 'New Subscribers: Last 30 Days';
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

        // 4) Fetch
        $subscribers = $query->get();
        // \Log::info('Subscribers fetched', ['count' => $subscribers->count()]);

        // 5) Reduce to only selected fields
        $records = $subscribers->map(function ($subscriber) use ($selectedFields) {
            $row = [];

            foreach ($selectedFields as $field) {
                if ($field === 'address') {
                    $fullAddress = implode(', ', array_filter([
                        data_get($subscriber, 'address'),
                    ]));
                    $row['address'] = $fullAddress;
                } else {
                    $row[$field] = data_get($subscriber, $field);
                }
            }

            return $row;
        });
        // \Log::info('Records mapped for PDF', ['records_count' => $records->count()]);

        // 6) Load logo as base64
        $logoPath = public_path('adminview/assets/images/email/logo_new.png');
        $logoType = pathinfo($logoPath, PATHINFO_EXTENSION);
        $logoData = file_exists($logoPath) ? file_get_contents($logoPath) : '';
        $logo = $logoData ? 'data:image/' . $logoType . ';base64,' . base64_encode($logoData) : '';
        \Log::info('Logo loaded', ['logo_exists' => file_exists($logoPath)]);

        // 7) Render PDF
        $pdfInstance = Pdf::loadView('reports::subscriber-reports.generate-reports.template', [
            'record' => $records,
            'fields' => $selectedFields,
            'filters' => $appliedFilters,
            'logo' => $logo,
            'report' => $report,
        ]);
        // \Log::info('PDF instance created');

        // 8) Save and return
        $fileName = 'Subscriber_report.pdf';
        $pdfDir = storage_path('app/pdfs/subscriber_reports');
        $filePath = $pdfDir . '/' . $fileName;

        if (!file_exists($pdfDir)) {
            mkdir($pdfDir, 0755, true);
            // \Log::info('PDF directory created', ['path' => $pdfDir]);
        }

        $pdfInstance->save($filePath);
        \Log::info('PDF saved', ['file_path' => $filePath]);

        if (!file_exists($filePath)) {
            // \Log::error('PDF file not found after save', ['file_path' => $filePath]);
            abort(404, 'PDF file not found.');
        }

        // \Log::info('Returning PDF download response', ['file_path' => $filePath]);

        return response()->download($filePath, $fileName)->deleteFileAfterSend(true);
    }

    // ==========***********==========
    public function exportCsv($id)
    {
        // return Excel::download(new SubscriberExport, 'subscribers.csv');
        return Excel::download(new SubscriberExport($id), 'subscribers_csv_report_' . $id . '.csv');
    }

    public function exportTable($id)
    {
    return Excel::download(new SubscriberTableExport($id), 'subscribers_table_report_' . $id . '.xlsx');
    }

    public function record()
    {
        $data = OrgSubscribers::with('subscription_payment_details')->get();
        return $data;
    }

}
