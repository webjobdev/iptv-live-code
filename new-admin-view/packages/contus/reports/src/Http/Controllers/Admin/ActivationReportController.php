<?php

namespace Contus\Reports\Http\Controllers\Admin;

use Carbon\Carbon;
use Contus\Base\Controller;
use Contus\Reports\Exports\ActivationAuditReport;
use Contus\Reports\Exports\CpsCsvReport;
use Maatwebsite\Excel\Facades\Excel;

final class ActivationReportController extends Controller
{

    public function exportCpsCsv($id)
    {
        return Excel::download(new ActivationAuditReport($id), 'activation_csv_report_' . $id . '.csv');
    }

}
