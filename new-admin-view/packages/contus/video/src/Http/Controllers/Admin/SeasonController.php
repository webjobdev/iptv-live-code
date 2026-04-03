<?php

/**
 * Seasons Controller
 *
 * To manage the Exams such as create, edit and delete
 *
 * @name       Seasons Controller
 * @version    1.0
 * @author     Contus Team <developers@contus.in>
 * @copyright  Copyright (C) 2018 Contus. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Http\Controllers\Admin;

use Contus\Base\Controller as BaseController;

class SeasonController extends BaseController {

/**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\View
   */
  public function getIndex() {
    return view ( 'video::admin.season.index');
  }

/**
   * get Grid template
   *
   * @return \Illuminate\Http\View
   */
  public function getGridlist() {
    return view ( 'video::admin.season.gridView' );
  }

}
