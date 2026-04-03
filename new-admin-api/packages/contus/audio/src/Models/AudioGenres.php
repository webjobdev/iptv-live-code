<?php

/**
 * Audio Genres Models.
 *
 * @name Audio Genres
 * @vendor Contus
 * @package Audio
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2019 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Audio\Models;

use Carbon\Carbon;
use Contus\Base\Helpers\StringLiterals;
use Contus\Base\Model;

class AudioGenres extends Model
{
    /**
     * The database table used by the model.
     *
     * @vendor Contus
     *
     * @package Audio
     * @var string
     */
    protected $table = 'audio_genres';

    protected $hidden = ['creator_id', 'updated_at', 'updator_id'];

    /**
     * The attributes that are mass assignable.
     *
     * @vendor Contus
     *
     * @package Audio
     * @var array
     */
    protected $fillable = ['genre_name', 'genre_slug', 'is_active'];

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
        $this->setDynamicSlug('genre_name', 'genre_slug');
    }

    /**
     * Method used to filter the users based on the request.
     *
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilter($query, $status)
    {
        if ($status == 'active') {
            $query->where(StringLiterals::ISACTIVE, 1);
        } else if ($status == 'in-active') {
            $query->where(StringLiterals::ISACTIVE, 0);
        }
        return $query;
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

}
