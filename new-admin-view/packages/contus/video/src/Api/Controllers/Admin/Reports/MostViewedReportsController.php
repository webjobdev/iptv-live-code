<?php
/**
 * Most Viewed video Reports Controller
 *
 * To manage the Reports of the application.
 *
 * @name       MostViewedReportsController Controller
 * @version    1.0
 * @author     Contus Team <developers@contus.in>
 * @copyright  Copyright (C) 2018 Contus. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Api\Controllers\Admin\Reports;

use Illuminate\Http\Request;
use Contus\Base\ApiController;
use Contus\Video\Repositories\Reports\ReportMostViewedRepository;

class MostViewedReportsController extends ApiController {
    public function __construct() {
        parent::__construct ();
        $this->repository = new ReportMostViewedRepository();
    }
}