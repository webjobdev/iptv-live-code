<?php

namespace Contus\Reports\Http\Controllers\Admin;

use Carbon\Carbon;
use Contus\Base\Controller;
use Contus\Reports\Exports\CpsCsvReport;
use Maatwebsite\Excel\Facades\Excel;

final class CpsReportcontroller extends Controller
{

    public function exportCpsCsv($id)
    {
        return Excel::download(new CpsCsvReport($id), 'cps_csv_report_' . $id . '.csv');
    }

    public function exportCpsODS($id)
    {
        return Excel::download(new CpsCsvReport($id), 'cps_ods_report_' . $id . '.ods');
    }

}
