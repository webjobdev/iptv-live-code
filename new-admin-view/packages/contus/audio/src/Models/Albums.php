<?php

/**
 * Albums Model
 *
 * Audio album management related model
 *
 * @name Albums
 * @version 1.0
 * @author Contus Team <developers@contus.in>
 * @copyright Copyright (C) 2018 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Audio\Models;

use Carbon\Carbon;
use Contus\Audio\Models\Artist;
use Contus\Audio\Models\AudioGenres;
use Contus\Audio\Models\AudioLanguageCategory;
use Contus\Audio\Models\Audios;
use Contus\Audio\Traits\AlbumTrait;
use Contus\Base\Model;

class Albums extends Model
{
    use AlbumTrait;

    /**
     * The database table used by the model.
     *
     * @vendor Contus
     *
     * @package Audio
     * @var string
     */
    protected $table = 'audio_albums';

    protected $hidden = ['created_at', 'creator_id', 'updated_at', 'updator_id'];

    protected $appends = ['genre_name', 'audio_language'];
    
    /**
     * The database table fill by the model.
     *
     * @vendor Contus
     *
     * @package Audio
     * @var string
     */
    protected $fillable = ['album_name', 'album_description', 'album_artist_id', 'audio_language_category_id', 'album_thumbnail', 'album_release_date', 'creator_id', 'updator_id'];

    /**
     * The attribute will used to generate url
     *
     * @var array
     */

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
        $this->setDynamicSlug('album_name', 'slug');
    }

    /**
     * Method to get the formated released date
     *
     * @vendor Contus
     * @return object
     */
    public function getAlbumReleaseDateAttribute($value)
    {
        return Carbon::parse($value)->format('M d Y');
    }

    /**
     * Method to get the formated released date
     *
     * @vendor Contus
     * @return object
     */
    public function getAlbumThumbnailAttribute($value)
    {
        return (!empty($value)) ? env('AWS_BUCKET_URL') . $value : '';
    }

    /**
     * hasMany relationship between albums and Artist
     */
    public function artist()
    {
        return $this->hasMany(Artist::class, 'id', 'album_artist_id');
    }

    /**
     * hasMany relationship between albums and audios
     */
    public function audios()
    {
        return $this->hasMany(Audios::class, 'album_id')->where('is_archived',0);
    }

    /**
     * Scope a query to only include active audios.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
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
        return $this->belongsTo(AudioGenres::class, 'genre_id', 'id')->select('id', 'genre_name');
    }

    /** Method to get the audio Genre Name
     *
     * @package Audio
     * @return String
     */
    public function getGenreNameAttribute()
    {
        return $this->genre()->whereId($this->genre_id)->value('genre_name');
    }

     /**
     * HasMany relationship between audio and album
     */
    public function audioLanguage()
    {
        return $this->belongsTo(AudioLanguageCategory::class, 'audio_language_category_id', 'id')->select('id', 'language_name');
    }

    /** Method to get the audio language Name
     *
     * @package Audio
     * @return String
     */
    public function getAudioLanguageAttribute()
    {
        return $this->audioLanguage()->whereId($this->audio_language_category_id)->value('language_name');
    }

}
