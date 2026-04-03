<?php

/**
 * Playlists Models.
 *
 * @name Playlists
 * @vendor Contus
 * @package Video
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2018 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Models;

use Carbon\Carbon;
use Contus\Video\Models\Playlists;
use Contus\Video\Models\VideoAdminPlaylist;
use Contus\Video\Models\Video;
use Contus\Video\Models\Group;
use Contus\Video\Models\PlaylistTranslation;
use Contus\Video\Models\PlaylistCategory;
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
    protected $table = 'playlists';

    protected $hidden = ['creator_id', 'updated_at', 'updator_id'];
    /**
     * The attributes that are mass assignable.
     *
     * @vendor Contus
     *
     * @package Audio
     * @var array
     */
    protected $fillable = ['name', 'slug', 'playlist_order',StringLiterals::ISACTIVE,'description','presenter'];

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
        $this->setDynamicSlug('name', 'slug');
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
     * @return Contus\Video\Models\Playlists
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
     * @param Contus\Video\Models\Playlists $playlists
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
    public function getPlaylistImageAttribute($value)
    {
        return (!empty($value)) ? env('AWS_BUCKET_URL') . $value : '';
    }
    /**
     * Method to fetch playlist audios
     * 
     * @vendor Contus
     * 
     * @package Video
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function playlistVideos(){
        return $this->belongsToMany(Video::class,'video_admin_playlists_tracks','playlist_id','video_id');
    }

    /**
     * Method to fetch playlist video order
     * 
     * @vendor Contus
     * 
     * @package Video
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function playlistVideosOrder(){
        return $this->hasMany(VideoAdminPlaylist::class,'playlist_id');
    }

     /**
     * HasOne relationship for playlist translation.
     */
    public function playlist_translation() {
        return $this->belongsTo ( PlaylistTranslation::class, 'id', 'playlist_id');
       }

       /**
     * HasMany relationship between videos and video_categories
     */
    public function playlistcategory()
    {
        return $this->hasMany(PlaylistCategory::class);
    }

 /**
     * belongsToMany relationship between collection and collections_videos
     */
    public function collections()
    {
        return $this->belongsToMany(Group::class, 'collection_playlists','playlist_id', 'group_id')->withTimestamps();
    }
}
