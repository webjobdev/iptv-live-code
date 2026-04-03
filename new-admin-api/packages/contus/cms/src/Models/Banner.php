<?php

namespace Contus\Cms\Models;

use Contus\Base\Model;
use Contus\Video\Models\Video;
use Carbon\Carbon;

class Banner extends Model {
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'banners';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [ 'title','type','extension','url','banner_image','mobile_image','video_image' ];

    protected $appends = ['banner_url', 'mobile_url'];

    /**
     * Constructor method
     * This is the common method to set hidden only for Front end customers
     */
    public function __construct() {
        parent::__construct ();
        $this->setHiddenCustomer ( [ 'id','is_active','updator_id','creator_id','created_at','updated_at' ] );
    }

    /**
     * function to automate operations while Saving
     */
    public function bootSaving() {
        $this->saveImage ( 'banner_image' );        
        $keys = array('dashboard_banner_image');
        $this->clearCache($keys);
    }

    public function videos() {
        return $this->belongsTo(Video::class, 'video_id', 'id');
    }

    public function getBannerUrlAttribute() {
        $value = $this->banner_image;
        return (!empty($value))?env('AWS_BUCKET_URL').$value:'';
    }

    public function getMobileUrlAttribute() {
        $value = $this->mobile_image;
        return (!empty($value))?env('AWS_BUCKET_URL').$value:'';
    }

    /**
     * Get the formated created date
     *
     * @return object
     */
    public function getFormattedCreatedDateAttribute()
    {
        return  Carbon::parse($this->created_at)->format('M d Y');
    }
}