<?php
/**
 * Reports Controller
 *
 * @name       ReportsController
 * @vendor     Contus
 * @package    Video
 * @version    1.0
 * @author     Contus<developers@contus.in>
 * @copyright  Copyright (C) 2018 Contus. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */

namespace Contus\Video\Http\Controllers\Admin;

use Contus\Base\Controller as BaseController;

class ReportsController extends BaseController {
   /**
   * class property is used to initiate the class
   *
   * @vendor     Contus
   * @package    Video
   * @var array
   */
    public function __construct() {
    }
    /**
     * Method to load the index of corresponding reports/analytics page
     * @vendor     Contus
     * @package    Video
     * @return \Illuminate\Http\View
     */
    public function getIndexRoute($route){
        switch ($route) {
            case 'most-commented':
                $response =  view ( 'video::admin.reports.most_commented.index' );      
            break;
            case 'most-favourite':
                $response =  view ( 'video::admin.reports.most_favourite.index' );      
            break;
            case 'top-category':
                $response =  view ( 'video::admin.reports.top_category.index' );      
            break;
            case 'region-wise-view':
                $response =  view ( 'video::admin.reports.region_wise_view.index' );      
            break;
            case 'most-viewed':
                $response =  view ( 'video::admin.reports.most_viewed.index' );      
            break;
           default:
                $response =  view('video::admin.dashboard.dashboard' );
           break;
        }
        return $response;
    }
    /**
    * Method to load the grid template of corresponding reports/analytics page
    *
    * @vendor     Contus
    * @package    Video
    * @return \Illuminate\Http\View
    */
    public function gridlist($route) {
        switch ($route) {
            case 'most_commented':
                $response =  view ( 'video::admin.reports.most_commented.gridView' );      
            break;
            case 'most_favourite':
                $response =  view ( 'video::admin.reports.most_favourite.gridView' );      
            break;
            case 'top_category':
                $response =  view ( 'video::admin.reports.top_category.gridView' );
            break;    
            case 'region_wise_view':
                $response =  view ( 'video::admin.reports.region_wise_view.gridView' );      
            break;
            case 'most_viewed':
                $response =  view ( 'video::admin.reports.most_viewed.gridView' );      
            break;
           default:
                $response =  view ( 'video::admin.dashboard.dashboard' );     
           break;
        }
        return $response;
      }
    /**
    * Show the dashboard page
    *
    * @vendor     Contus
    * @package    Video
    * @return \Illuminate\Http\View
    */
    public function getIndex() {
        return view ( 'video::admin.reports.reports' );
    }
    /**
    * Method to show the video analytics page
    *
    * @vendor     Contus
    * @package    Video
    * @return \Illuminate\Http\View
    */
    public function getAnalyticsvideo() {
        return view ( 'video::admin.reports.video' );
    }
}