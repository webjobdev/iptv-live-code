<?php

/**
 * Artist Models.
 *
 * @name Artist
 * @vendor Contus
 * @package Audio
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Playlist\Models;

use Carbon\Carbon;
use Contus\Playlist\Models\Albums;
use Contus\Playlist\Models\Audios;
use Contus\Base\Contracts\AttachableModel;
use Contus\Base\Helpers\StringLiterals;
use Contus\Base\Model;
use Symfony\Component\HttpFoundation\File\File;

class Artist extends Model implements AttachableModel
{
    /**
     * The database table used by the model.
     *
     * @vendor Contus
     *
     * @package Audio
     * @var string
     */
    protected $table = 'audio_artists';

    protected $hidden = ['creator_id', 'updated_at', 'updator_id'];

    /**
     * The attributes that are mass assignable.
     *
     * @vendor Contus
     *
     * @package Audio
     * @var array
     */
    protected $fillable = ['artist_name', 'artist_biography', StringLiterals::ISACTIVE];

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
        $this->setDynamicSlug('artist_name', 'slug');
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
     * Get File Information Model
     * the model related for holding the uploaded file information
     *
     * @vendor Contus
     *
     * @package Artist
     * @return Contus\Playlist\Models\Artist
     */
    public function getFileModel()
    {
        return $this;
    }
    /**
     * Set the file to Staplaer
     *
     * @param \Symfony\Component\HttpFoundation\File\File $file
     * @param string $storagePath
     * @return void
     */
    public function setFile(File $file, $config)
    {
        $this->image_url = url("$config->storage_path/" . $file->getFilename());
        $this->image_path = $file->getPathname();

        return $this;
    }
    /**
     * Store the file information to database
     * if attachment model is already has record will update
     *
     * @param Contus\Playlist\Models\Artist $artist
     * @return boolean
     */
    public function upload(Artist $artist)
    {
        return $artist->save();
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
    public function getArtistThumbnailAttribute($value)
    {
        return (!empty($value)) ? env('AWS_BUCKET_URL') . $value : '';
    }

    /**
     * belongsTo relationship between Artist and Album
     */
    public function album()
    {
        return $this->belongsTo(Albums::class, 'id', 'album_artist_id')->active();
    }

    /**
     * belongsTo relationship between Artist and Audio
     */
    public function audio()
    {
        return $this->hasMany(Audios::class, 'audio_artist_id')->active();
    }

}
