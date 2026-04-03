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
use Contus\Video\Models\SeasonTranslation;

class Season extends Model {
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'seasons';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [ 'title','is_active'];

    protected $url = ['image'];



    /**
     * Constructor method
     * sets hidden for customers
     */
    public function __construct() {
        parent::__construct ();
    }
    
    /**
     * Get the formated created date
     *
     * @return object
     */
    public function getFormattedCreatedDateAttribute()
    {
        if ($this->created_at == null) {
            $date = '-';
        } else {
            $date = Carbon::parse($this->created_at)->format('M d Y');
        }
        return $date;
    }

    public function SeasonTranslation()
    {
        return $this->hasMany(SeasonTranslation::class,'season_id');
    }

}
