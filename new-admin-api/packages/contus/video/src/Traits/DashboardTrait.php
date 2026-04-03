<?php
/**
 * Dashboard Trait
 *
 * To manage the functionalities related to the dashboard
 *
 * @vendor Contus
 *
 * @package Dashboard
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2018 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Traits;

use Carbon\Carbon;  

trait DashboardTrait {
    /**
     * Method to get the date based on the date filter
     * @param int $dateType
     * 
     * @return string $date
     */
    public function dateFilter($dateType){
        $date = '';
        switch ($dateType){
            case '4' :
                $date = Carbon::now ()->subDays ( 7 );
            break;
            case '3' :
                $date = Carbon::now ()->subDays ( 30 );
            break;
            case '2' :
                $date = Carbon::now ()->subDays ( 365 );
            break;
            case '1':
                $date = '';
            break;
            default:
            break;
        }
        return $date;
    }
}