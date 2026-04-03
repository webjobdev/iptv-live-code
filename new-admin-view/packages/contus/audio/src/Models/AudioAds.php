<?php

/**
 * AudioAds Models.
 *
 * @name AudioAds
 * @vendor Contus
 * @package Audio
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2018 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */

namespace Contus\Audio\Models;

use Carbon\Carbon;
use Contus\Base\Helpers\StringLiterals;
use Contus\Base\Model;

class AudioAds extends Model
{
    /**
     * The database table used by the model.
     *
     * @vendor Contus
     *
     * @package Audio
     * @var string
     */
    protected $table = 'audio_ads';

    protected $hidden = ['creator_id', 'updated_at', 'updator_id'];

    /**
     * The attributes that are mass assignable.
     *
     * @vendor Contus
     *
     * @package Audio
     * @var array
     */
    protected $fillable = ['ad_name', 'ad_url', StringLiterals::ISACTIVE];

    /**
     * Constructor method
     * sets hidden for customers
     */
    public function __construct()
    {
        parent::__construct();
        $this->setHiddenCustomer(['id', 'is_active', 'updated_at', 'created_at']);
    }

    /**
     * funtion to automate operations while Saving
     */
    public function bootSaving()
    {
        $this->setDynamicSlug('ad_name', 'ad_slug');
    }

    /**
     * Get the formated created date
     *
     * @return object
     */
    public function getFormattedCreatedDateAttribute()
    {
        return Carbon::parse($this->created_at)->format('M d Y');
    }

    /**
     * Method to get the formated Artist Thumbnail
     *
     * @vendor Contus
     * @return object
     */
    public function getAdImageAttribute($value)
    {
        return (!empty($value)) ? env('AWS_BUCKET_URL') . $value : '';
    }

    /**
     * Method to get the formated Artist Thumbnail
     *
     * @vendor Contus
     * @return object
     */
    public function getAudioAdAudioUrlAttribute($value)
    {
        return (!empty($value)) ? env('AWS_BUCKET_URL') . $value : '';
    }

}
