<?php

/**
 * Individual Allowed Countries Model.
 *
 * @name Individual Allowed Countries
 * @vendor Contus
 * @package Video
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2018 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Models;

use Contus\Base\MongoModel;
use Contus\Base\Model;

class individualAllowedCountries extends MongoModel{
    protected $collection = 'individual_allowed_countries';
    protected $connection = 'mongodb';
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'video_id','country_id', 'region_id', 'country_code', 'region_code', 'created_at', 'updated_at'
    ];
}