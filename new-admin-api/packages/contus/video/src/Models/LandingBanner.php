<?php

/**
 * Season Model for seasons table in database
 *
 * @name Season
 * @vendor Contus
 * @package Video
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2018 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Models;

use Contus\Base\Model;
use Carbon\Carbon;

class LandingBanner extends Model {
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'landingbanner';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [ 'title','is_active','season_order','banner_image','mobile_image','video_image'];

    // protected $url = ['image'];



    /**
     * Constructor method
     * sets hidden for customers
     */
    public function __construct() {
        parent::__construct ();
    }
    
}
