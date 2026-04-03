<?php

/**
 * VideoTrait
 *
 * To manage the functionalities related to the Videos module from Video Controller
 *
 * @vendor Contus
 *
 * @package Video
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Traits;

use Contus\Base\Helpers\StringLiterals;
use Contus\Customer\Models\Customer;
use Contus\Video\Models\Group;
use Carbon\Carbon;
use Contus\Video\Models\Like;
use Contus\Video\Models\Comment;
use Contus\Video\Models\PlaylistVideos;
use Contus\Video\Models\UserPlaylist;
use Contus\Video\Models\Category;
use Contus\Video\Models\VideoTranslation;
use Contus\Video\Models\FavouriteVideo;
use Contus\Cms\Models\Banner;
use Contus\Video\Models\VideoMetaData;
use Contus\Video\Models\VideoAds;
use Contus\Video\Models\Ads;
use Contus\Video\Models\VideoXrayCast;
use Contus\Video\Models\Cast;
use Contus\Video\Models\VideoAudioUploads;

trait VideoTrait
{
    /**
     * HasMany relationship between videos and video_posters
     */
    public function recentlyViewed()
    {
        return $this->belongsToMany(Customer::class, 'recently_viewed_videos', 'video_id', 'customer_id');
    }

    
    /**
     * Method for BelongsToMany relationship between video and favourite_videos
     *
     * @vendor Contus
     *
     * @package Customer
     * @return unknown
     */
    public function favourite()
    {
        return $this->belongsToMany(Customer::class, 'favourite_videos');
    }

    public function favouriteVideo()
    {
        return $this->hasMany(FavouriteVideo::class,'video_id','id');
    }

    /**
     * belongsToMany relationship between collection and collections_videos
     */
    public function group()
    {
        return $this->belongsToMany(Group::class, 'collections_videos', StringLiterals::VIDEOID, 'group_id')->withTimestamps();
    }

    public function getCollectionAttribute()
    {
        if ($this->group()->count() > 0) {
            return $this->group()->first()->toArray();
        }
        return new \stdClass();
    }

    /**
     * Set explicit model condition for mobile
     *
     * {@inheritdoc}
     *
     * @see \Contus\Base\Model::whereliveVideo()
     *
     * @return object
     */
    public function whereliveVideos()
    {
        if (config()->get('auth.providers.users.table') === 'customers') {
            return $this->where('is_active', '1')->where('job_status', 'Complete')->where('is_archived', 0)->where('is_live', 1)->where('liveStatus', '!=', 'complete');
        }
    }
    
    /**
     * Funtion to append the demo feature in video listing page and detail page
     *
     * @return boolean
     */
    public function getIsDemoAttribute()
    {
        if (config()->get('auth.providers.users.table') === 'customers') {
            return (auth()->user() && auth()->user()->isExpires()) ? 0 : 1;
        }
    }
     /**
     * Funtion to append the show or hide comment feature in video listing page and detail page
     *
     * @return boolean
     */
    public function getIsShowHideCommentAttribute() {
        return config()->get('settings.settings.website-settings.show_hide_comments');
    }

     /**
     * Funtion to append the show or hide likes feature in video listing page and detail page
     *
     * @return boolean
     */
    public function getIsShowHideLikesAttribute() {
        return config()->get('settings.settings.website-settings.show_hide_likes');
    }

    /**
     * Check whether user is liked the video or not
     *
     * @return object
     */
    public function getIsLikeAttribute()
    {
        $likeStatus = false;
        if (auth()->user()) {
            $likeStatus = Like::where('user_id', auth()->user()->id)->where('video_id', (int) $this->id)->where('type', Like::TYPE['like'])->exists();
        }
        return ($likeStatus) ? 1 : 0;
    }

    /**
     * Check whether user is disliked the video or not
     *
     * @return object
     */
    public function getIsDislikeAttribute()
    {
        $likeStatus = false;
        if (auth()->user()) {
            $likeStatus = Like::where('user_id', auth()->user()->id)->where('video_id', (int) $this->id)->where('type', Like::TYPE['dislike'])->exists();
        }
        return ($likeStatus) ? 1 : 0;
    }

    /**
     * Get the count of liked videos
     *
     * @return object
     */
    public function getLikeCountAttribute()
    {
        return Like::where('video_id', (int) $this->id)->where('type', Like::TYPE['like'])->count();
    }

    /**
     * Get the count of disliked videos
     *
     * @return object
     */
    public function getDislikeCountAttribute()
    {
        return Like::where('video_id', (int) $this->id)->where('type', Like::TYPE['dislike'])->count();
    }

    /**
     * Get the count of comments videos
     *
     * @return object
     */
    public function getCommentsCountAttribute()
    {
        return Comment::where('video_id', (int) $this->id)->count();
    }

    /**
     * Get the count of comments videos
     *
     * @return object
     */
    public function getFavouriteCountAttribute()
    {
        return FavouriteVideo::where('video_id', (int) $this->id)->count();
    }

    public function getIsFavouriteAttribute()
    {
        $favStatus = false;
        if (!empty(authUser()->id)) {
            $favStatus = FavouriteVideo::where('customer_id', authUser()->id)->where('video_id', (int) $this->id)->exists();
        }
        
        return ($favStatus) ? 1 : 0;
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
     * Get the formated published date
     *
     * @return object
     */
    public function getFormattedPublishedDateAttribute()
    {
        return Carbon::parse($this->published_on)->format('M d Y');
    }

    /**
     * Method for BelongsToMany relationship between video and favourite_videos
     *
     * @vendor Contus
     *
     * @package Customer
     * @return unknown
     */
    public function userPlaylist()
    {
        return $this->belongsToMany(UserPlaylist::class, 'playlist_videos', 'video_id', 'playlist_id');
    }

    /**Category
    * Get the category name
    * @return string
    */
    public function categoryName($id)
    {
        $categoryString = '';
        $category = Category::with('parent_category')->find($id);
        if (!empty($category->parent_category)) {
            $categoryString = $category->parent_category->title . ',';
        }
        return $categoryString . $category->title;
    }

    /**
    * Get the genre name
    * @return string
    */
    public function genreName($id)
    {
        return Group::find($id)->name;
    }

    /**
    * Get the genre name
    * @return string
    */
    public function getGenreName()
    {
        $genre = $this->group()->first();
        if (!empty($genre)) {
            return $genre->name;
        }
        return '';
    }

    /**
    * Get the category name
    * @return string
    */
    public function getCategoryName()
    {
        $categories = $this->categories()->first();
        if (!empty($categories)) {
            return $categories->title;
        }
        return '';
    }

    /**
     * Get the tags names
     * @return string
     */
    public function tagNames()
    {
        return implode(',', $this->tags()->get()->pluck('name')->toArray());
    }
    /**
     * Method to record video analytics data 
     * @param $video array
     * 
     * @return boolean
     */
    public function addVideoAnalytics($video){
        $ip = '';
        $videoAnalyticsData = array();
        /** This is call to the helper method to the get the IP address */
        $ip = getIPAddress();
         /** This is call to a method to get the current logged in user country based on the IP */
         $getcurrentIPLocation = Location::get($ip);
         /** Call to method to get the platform (Web, ios or android) of the request */
         $platform = getPlatform();
         $customerId = (!empty(authUser()->id))?authUser()->id:0;
         $videoAnalyticsData = [
             'video_id'=>$video->id,
             'video_title'=>$video->title,
             'customer_id' => $customerId,
             'country' => $getcurrentIPLocation->countryName,
             'platform' => $platform,
         ];
         /** Set validator to check if all the parameters exist needed for video analytics */
        $validator = Validator::make($videoAnalyticsData, [
            'video_id' => 'required|integer',
            'video_title' => 'required|string',
            'customer_id' => 'required|integer',
            'country' => 'required|string',
            'platform' => 'required|string',
        ]);
        if ($validator->fails()) {
            $messages = $validator->messages()->toArray();
            foreach($messages as $message){
                app('log')->error(' ###File : VideoTrait.php ##Message : The video analytics insertion failed  ' .' #Error : ' . $message[0]);
            }
       }else{
        $videoAnalytic = new VideoAnalytic();
            $videoAnalytic->fill($videoAnalyticsData);
            return ($videoAnalytic->save())?true:false;
        }
        return false;
    }

    /**
     * belogsToMany relationship between video and video_translation
     */
    public function videoTranslation() {
        return $this->hasMany(VideoTranslation::class, 'video_id');
    }

    public function Banner()
    {
        return $this->hasOne(Banner::class, 'video_id');
    }

    /**
     * return true is banner image in this video
     */
    public function getIsBannerAttribute($id)
    {
        $bannerinfo = $this->banner()->exists();
        if($bannerinfo)
        {
            return 1;
        }
        else{
            return 0;
        }
        
    }

    /**
     * Video Metadata
     */

    public function videoMetaData()
    {
        return $this->hasOne(VideoMetaData::class, 'video_id');
    }

    public function ads() {
        return $this->belongsToMany(Ads::class, 'video_ads', 'video_id', 'ads_id');
    }

    public function cast() {
        return $this->belongsToMany(Cast::class, 'video_x_ray_cast', 'video_id', 'x_ray_cast_id');
    }
    

    public function getSpriteImageAttribute($value) {
        $imagePath = '';
        if($value != '') {
            $imagePath = env('AWS_BUCKET_URL').$value;
        }
        return $imagePath;
    }
    /**
     * Obtain audio tracks related to a video
     */
    public function videoAudioTracks(){
        return $this->hasMany(VideoAudioUploads::class);
    }
   
}
