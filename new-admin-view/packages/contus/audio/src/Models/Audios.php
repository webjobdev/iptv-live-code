<?php

/**
 * Audios Model
 *
 * Audio management related model
 *
 * @name Audios
 * @version 1.0
 * @author Contus Team <developers@contus.in>
 * @copyright Copyright (C) 2018 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Audio\Models;

use Carbon\Carbon;
use Contus\Audio\Models\Albums;
use Contus\Audio\Models\AudioGenres;
use Contus\Audio\Traits\AudioTrait;
use Contus\Base\Model;
use Contus\User\Models\User;

class Audios extends Model
{
    use AudioTrait;

    /**
     * The database table used by the model.
     *
     * @vendor Contus
     *
     * @package Audio
     * @var string
     */
    protected $table = 'audios';

    /**
     * The attributes that are mass assignable.
     *
     * @vendor Contus
     *
     * @package Audio
     * @var array
     */
    protected $fillable = ['audio_title', 'album_id', 'audio-artist_id', 'audio_description'];

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
        $this->setDynamicSlug('audio_title', 'slug');
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
     * Get the formated updated date
     *
     * @return object
     */
    public function getFormattedUpdatedDateAttribute()
    {
        return Carbon::parse($this->updated_at)->format('M d Y');
    }

    /**
     * Method to get the formated released date
     *
     * @vendor Contus
     * @return object
     */
    public function getAudioThumbnailAttribute($value)
    {
        return (!empty($value)) ? env('AWS_BUCKET_URL') . $value : '';
    }

    /**
     * HasMany relationship between audio and album
     */
    public function album()
    {
        return $this->belongsTo(Albums::class, 'album_id', 'id')->select('id', 'album_artist_id', 'album_name', 'audio_language_category_id', 'album_release_date', 'album_thumbnail', 'genre_id');
    }

    /**
     * HasMany relationship between audio and Artist
     */
    public function artist()
    {
        return $this->belongsTo(Artist::class, 'audio_artist_id', 'id')->select('id', 'artist_name', 'artist_biography', 'artist_thumbnail');
    }

    /**
     * HasMany relationship between audio and user
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'updator_id')->select('id', 'name');
    }

    /**
     * Scope a query to only include active audios.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', 1)->where('is_archived', 0);
    }
    /**
     * Method to set is_active value to boolean
     * 
     * @vendor Contus
     * @package Audio
     * @param int $value
     * @return boolean 
     */
    public function getIsActiveAttribute($value){
        return ($value) ? true : false;
    }


     /**
     * HasMany relationship between audio and album
     */
    public function genre()
    {
        return $this->belongsTo(AudioGenres::class, 'audio_genre_id', 'id')->select('id', 'genre_name');
    }

    /** Method to get the audio Genre Name
     *
     * @package Audio
     * @return String
     */
    public function getGenreNameAttribute()
    {
        return $this->genre()->whereId($this->audio_genre_id)->value('genre_name');
    }
}
