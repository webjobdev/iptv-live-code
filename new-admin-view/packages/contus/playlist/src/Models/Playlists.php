<?php

/**
 * Playlists Models.
 *
 * @name Playlists
 * @vendor Contus
 * @package Audio
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2018 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Playlist\Models;

use Carbon\Carbon;
use Contus\Playlist\Models\Playlists;
use Contus\Base\Contracts\AttachableModel;
use Contus\Base\Helpers\StringLiterals;
use Contus\Base\Model;
use Symfony\Component\HttpFoundation\File\File;

class Playlists extends Model implements AttachableModel
{

    /**
     * The database table used by the model.
     *
     * @vendor Contus
     *
     * @package Audio
     * @var string
     */
    protected $table = 'audio_admin_playlists';

    protected $hidden = ['creator_id', 'updated_at', 'updator_id'];
    /**
     * The attributes that are mass assignable.
     *
     * @vendor Contus
     *
     * @package Audio
     * @var array
     */
    protected $fillable = ['playlist_name', 'playlist_thumbnail', 'playlist_slug', StringLiterals::ISACTIVE];

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
        $this->setDynamicSlug('playlist_name', 'playlist_slug');
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
     * @package Audio
     * @return Contus\Playlist\Models\Playlists
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
     * @param Contus\Playlist\Models\Playlists $playlists
     * @return boolean
     */
    public function upload(Playlists $playlists)
    {
        return $playlists->save();
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
     * Method to get the formated Playlist Thumbnail
     *
     * @vendor Contus
     * @return object
     */
    public function getPlaylistThumbnailAttribute($value)
    {
        return (!empty($value)) ? env('AWS_BUCKET_URL') . $value : '';
    }
    /**
     * Method to fetch playlist audios
     * 
     * @vendor Contus
     * 
     * @package Audios
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function playlistAudios(){
        return $this->belongsToMany(Audios::class,'audio_admin_playlist_tracks','playlist_id','audio_id');
    }

}
