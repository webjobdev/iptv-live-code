<?php

/**
 * Settings
 *
 * To manage the functionalities related to settings
 * @name       Settings
 * @version    1.0
 * @author     Contus<developers@contus.in>
 * @copyright  Copyright (C) 2016 Contus. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Cms\Models;

use Contus\Base\Model;

class Setting extends Model {
  /**
   * The database table used by the model.
   *
   * @var string
   */
  protected $table = 'settings';
  
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [ 
      'setting_name',
      'setting_value',
      'display_name',
      'type',
      'option',
      'class',
      'order',
      'setting_category_id',
      'description' 
  ];
  
}