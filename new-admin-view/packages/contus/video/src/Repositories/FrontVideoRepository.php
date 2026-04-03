<?php

/**
 * Front Video Repository
 *
 * To manage the functionalities related to videos for the frontend
 *
 * @name FrontVideoRepository
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 *
 */

namespace Contus\Video\Repositories;

use Illuminate\Support\Facades\DB;
use Contus\Video\Models\Video;
use Contus\Video\Models\Category;
use Contus\Video\Models\Collection;
use Contus\Video\Models\Group;
use Contus\Notification\Models\Notification;
use Carbon\Carbon;
use Contus\Customer\Models\Customer;
use Illuminate\Database\Eloquent\Model;

class FrontVideoRepository extends VideoRepository
{
    /**
     * Function to get all video to frontend with filters and search
     *
     * @vendor Contus
     *
     * @package video
     * @return array
     */
    public function fgetallVideo($searchwidget = true)
    {
        $fields = 'videos.id, videos.title, videos.slug, videos.description, videos.short_description, videos.thumbnail_image, videos.selected_thumb, videos.hls_playlist_url, videos.video_duration, videos.id as is_favourite, videos.id as collection';

        $this->video = $this->video->whereCustomer()->where('is_live', '!=', 1)->has('categories')->orderBy('video_order', 'desc');
        if ($this->request->has('search') && $this->request->search != null) {         
            $this->video = $this->video->where('title', 'like', '%' . $this->request->search . '%');
        }
        if ($this->request->has('category') && $this->request->category != null) {     
            $categoryId = $this->category->whereIn($this->getKeySlugorId(), explode(',', $this->request->category))->pluck('id');
            $this->video = $this->video->orderBy('video_order', 'desc')->whereHas('videocategory', function ($q) use ($categoryId) {
                $q->whereIn('category_id', $categoryId);
            });
        } else {         
            $categoryId = $this->category->whereIn($this->getKeySlugorId(), array_keys($this->categoryRepository->getAllCategories($this->request->main_category)))->pluck('id');
            $this->video = $this->video->orderBy('video_order', 'desc')->whereHas('videocategory', function ($q) use ($categoryId) {
                $q->whereIn('category_id', $categoryId);
            });
        }
        if ($this->request->has('tag') && $this->request->tag != null) {
            $this->video = $this->video->whereHas('tags', function ($q) {
                $q->whereIn('tag_id', explode(',', $this->request->tag));
            });
        }
        if ($searchwidget) {
            if ($this->request->header('x-request-type') == 'mobile') {
                $video = $this->video->leftJoin('favourite_videos as f1', function ($j) {
                    $j->on('videos.id', '=', 'f1.video_id')->on('f1.customer_id', '=', DB::raw((auth()->user()) ? auth()->user()->id : 0));
                })->selectRaw($fields)->groupBy('videos.id');
                if ($this->request->has('video_id')) {
                    $video = $video->where('videos.id', '!=', $this->request->video_id);
                }
                $video = $video->paginate(9)->toArray();
            } else {
             
                $video = $this->video->leftJoin('favourite_videos as f1', function ($j) {
                    $j->on('videos.id', '=', 'f1.video_id')->on('f1.customer_id', '=', DB::raw((auth()->user()) ? auth()->user()->id : 0));
                })->selectRaw($fields)->groupBy('videos.id')->with(['tags', 'videocategory.category'])->paginate(9)->toArray();
            }
        } else {
            $video = $this->video->select('title', $this->getKeySlugorId())->take(10)->get();
        }
        return $video;
    }

    /**
     * function to get all tags
     *
     * @vendor Contus
     *
     * @package video
     * @return unknown
     */
    public function getallTags() {
     if ($this->request->category) {
     $categoryId = $this->category->whereIn ( $this->getKeySlugorId (), explode ( ',', $this->request->category ) )->pluck('id');
     } else {
     $categoryId = $this->category->whereIn ( $this->getKeySlugorId (), array_keys ( $this->categoryRepository->getAllCategories ( $this->request->main_category ) ) )->pluck('id');
     }
     return $this->tag->whereHas ( 'videos.categories', function ($query) use ($categoryId) {
      $query->whereIn ( 'categories.id', $categoryId );
     } )->pluck ( 'name', 'id' );
    }

    /**
     * Get Live Video Notification lists
     */
    public function getLiveVideoNotification()
    {
        $savedVideos = Video::where('is_archived', 0)->where('is_active', 1)->where('job_status', 'Complete')->where('notification_status', 0)->where('is_live', 0)->orderBy('video_order', 'desc')->get();
        $liveVideos = Video::where('is_archived', 0)->where('is_active', 1)->where('liveStatus', 'ready')->where('job_status', 'Complete')->whereRaw('DATE(scheduledStartTime) = "' . Carbon::now()->tomorrow()->toDateString() . '"')->where('notification_status', 0)->where('is_live', 1)->orderBy('scheduledStartTime', 'asc')->get();
        if ($liveVideos->toArray() || $savedVideos->toArray()) {
            $customer = Customer::where('email', '!=', '')->where('is_active', 1)->where('notify_newsletter', 0)->get();
            $vCount = $savedVideos->count();
            $lCount = $liveVideos->count();
            $vHtml = '';
            if ($savedVideos->toArray()) {
                for ($i = 0; $i < 5; $i++) {
                    if (!isset($savedVideos [$i])) {
                        continue;
                    }
                    $vHtml .= '<tr><td><a target="_blank" href="' . env('LS_TYPE_FRONT') . '/video-detail/' . $savedVideos [$i]->slug . '">' . $savedVideos [$i]->title . '</a></td></tr>';
                }
                $vHtml = '<p>Check out the latest ' . $vCount . ' videos added at ' . config()->get('settings.general-settings.site-settings.site_name') . '</p>
                <table>' . $vHtml . '</table><p><a target="_blank" href="' . env('LS_TYPE_FRONT') . '">View more videos from our site</a><p>';
                Video::where('is_archived', 0)->where('is_active', 1)->where('job_status', 'Complete')->where('notification_status', 0)->where('is_live', 0)->update(['notification_status' => 1]);
            }
            $LHtml = '';
            if ($liveVideos->toArray()) {
                for ($i = 0; $i < 5; $i++) {
                    if (!isset($liveVideos [$i])) {
                        continue;
                    }
                    $LHtml .= '<tr><td><a target="_blank" href="' . env('LS_TYPE_FRONT') . '/video-detail/' . $liveVideos [$i]->slug . '">' . $liveVideos [$i]->title . '</a></td></tr>';
                }
                $LHtml = '<p>' . config()->get('settings.general-settings.site-settings.site_name') . ' has scheduled ' . $lCount . ' videos for tomorrow.</p>
                <table>' . $LHtml . '</table><p><a target="_blank" href="' . env('LS_TYPE_FRONT') . '">View all live videos from our site</a><p>';
                $LHtml = '<h2>Live videos scheduled for tomorrow.&nbsp;</h2><p>' . $LHtml . '</p>';
                Video::where('is_archived', 0)->where('is_active', 1)->where('liveStatus', 'ready')->where('job_status', 'Complete')->whereRaw('DATE(scheduledStartTime) = "' . Carbon::now()->tomorrow()->toDateString() . '"')->where('notification_status', 0)->where('is_live', 1)->orderBy('scheduledStartTime', 'asc')->update(['notification_status' => 1]);
            }
            $html = '<h2>##NAME##, </h2>' . $vHtml . $LHtml;
            foreach ($customer as $c) {
                $content = str_replace(['##NAME##'], [$c->name], $html);
                $this->email($c, 'New videos in ' . config()->get('settings.general-settings.site-settings.site_name'), $content);
            }
            return true;
        }
        return false;
    }

    /**
     * function to get video with complete information using slug
     *
     * @return unknown
     */
    public function getVideoSlug($slug)
    {   
        $this->video = new Video ();
        $this->video = $this->video->whereCustomer()->where('is_active', 1)->where('videos.' . $this->getKeySlugorId(), $slug);
        if (is_null($this->video->first())) {
         $this->throwJsonResponse(false, 404, trans('video::videos.slugResponse'));
        }
        $this->video = $this->video->leftJoin('favourite_videos as f1', function ($j) {
            $j->on('videos.id', '=', 'f1.video_id')->on('f1.customer_id', '=', \DB::raw((auth()->user()) ? auth()->user()->id : 0));
        })->selectRaw('videos.*,videos.id as is_favourite, videos.id as collection')->groupBy('videos.id')->with(['categories']);
        
        $this->video = ($this->request->header('x-request-type') == 'mobile') ? $this->video->first() : $this->video->with('tags')->first();
        
        $this->video ['comments_count'] = $this->video->comments()->where('is_active', 1)->get()->count();
        return $this->video;
    }

    /**
     * function to get comments for video using slug
     *
     * @return unknown
     */
    public function getCommentsVideoSlug($slug, $getCount = 10, $paginate = true)
    {
        $video = new Video ();
        $video = $video->whereCustomer()->where($this->getKeySlugorId(), $slug)->first();
        if ($video->comments()) {
            $video = $video->comments()->with(['ReplyComment.admin', 'ReplyComment.customer', 'admin', 'customer'])->orderBy('id', 'desc');
            if (config()->get('auth.providers.users.table') === 'customers') {
                $video = $video->where('is_active', 1);
            }
            if ($paginate) {
                $video = $video->paginate($getCount)->toArray();
            } else {
                $video = $video->take($getCount)->get();
            }
            return $video;
        }
        return [];
    }

    /**
     * function to get live related videos
     *
     * @return object
     */
    public function getLiverelatedVideos($slug)
    {
        return $this->video->whereliveVideo()->where($this->getKeySlugorId(), '!=', $slug)->orderBy('scheduledStartTime', 'desc')->take(3)->get();
    }

    /**
     * function to get scheduled as well as upcomming live video lists
     *
     * @return array
     */
    public function getAllLiveVideos()
    {
        $videos = $this->video->whereallliveVideo()->orderBy('scheduledStartTime', 'ASC')->get()->toArray();
        return ['data' => $videos, 'next_page_url' => null, 'total' => count($videos)];
    }

    /**
     * function to get recorded live videos
     *
     * @return object
     */
    public function getrecordedLiveVideos($record = '', $getCount = 9, $paginate = true)
    {
        if ($record) {
            $videos = $this->video->whereRecordedliveVideo()->orderBy('id', 'desc')->get();
        } else {
            if ($this->request->header('x-request-type') == 'mobile') {
                $videos = $this->video->whereRecordedliveVideo()->orderBy('id', 'desc')->take(5)->get();
            } else {
                $videos = $this->video->whereRecordedliveVideo()->orderBy('id', 'desc');
                if ($paginate) {
                    $videos = $videos->paginate($getCount)->toArray();
                } else {
                    $videos = $videos->take($getCount)->get();
                }
            }
        }
        return $videos;
    }

    /**
     * Update live stream details
     *
     * @return object
     */
    public function getLiveTime()
    {
        return Video::where('is_active', '1')->where('liveStatus', '!=', 'complete')->where('scheduledStartTime', '!=', '')->where('is_archived', 0)->where('is_live', 1)->select('scheduledStartTime')->first()->orderBy('scheduledStartTime', 'desc');
    }

    /**
     * function to get live videos for widget display
     *
     * @return object
     */
    public function getOnlyLiveVideos($record = '')
    {
        $videos = new Video ();
        $serverTime = new \DateTime (date("Y-m-d H:i:s", time()));
        $videos = $videos->whereliveVideo()->orderBy('scheduledStartTime', 'asc') ;
        if ($record) {
            $liverecord = $videos->take($record)->get()->makeHidden('liveStatus')->toArray();
            foreach ($liverecord as $key => $value) {
                $checklivetime = new \DateTime ($value ['scheduledStartTime']);
                $liverecord [$key] ['liveVideoTime'] = ($checklivetime <= $serverTime);
            }
        } else {
            $liverecord = $videos->take(4)->get()->makeHidden('liveStatus');
        }
        return $liverecord;
    }

    /**
     * function to get recent videos for video using slug
     *
     * @return array
     */
    public function getVideoByType($type)
    {
        $video = $this->video->whereCustomer();
        if ($type == 'banner') {
            $video = $video->leftJoin('favourite_videos as f1', function ($j) {
                $j->on('videos.id', '=', 'f1.video_id')->on('f1.customer_id', '=', DB::raw((auth()->user()) ? auth()->user()->id : 0));
            })->selectRaw('videos.*,count(f1.video_id) as is_favourite')->groupBy('videos.id')->with(['categories.parent_category.parent_category'])->where('is_live', '==', 0)->orderBy('id', 'desc')->take(5)->get();
        } else if ($type == 'recent') {
            $video = $this->video->where('is_active', '1')->where('job_status', 'Complete')->where('is_archived', 0)->leftJoin('recently_viewed_videos as f1', function ($j) {
                $j->on('videos.id', '=', 'f1.video_id');
            })->where('f1.customer_id', '=', DB::raw((auth()->user()) ? auth()->user()->id : 0))->selectRaw('videos.*')->groupBy('videos.id')->with(['categories.parent_category.parent_category'])->where('is_live', '==', 0)->orderBy('id', 'desc')->take(4)->get();
            foreach ($video as $k => $v) {
                $video [$k] ['is_favourite'] = $v->authfavourites()->get()->count();
            }
            if (!count($video) > 0) {
                $video = $this->video->where('is_active', '1')->where('job_status', 'Complete')->where('is_archived', 0)->where('trailer_status', 1)->leftJoin('favourite_videos as f1', function ($j) {
                    $j->on('videos.id', '=', 'f1.video_id')->on('f1.customer_id', '=', DB::raw((auth()->user()) ? auth()->user()->id : 0));
                })->selectRaw('videos.*,count(f1.video_id) as is_favourite')->groupBy('videos.id')->with(['categories.parent_category.parent_category'])->where('is_live', '==', 0)->orderBy('id', 'desc')->take(4)->get();
            }
        } else if ($type == 'trending') {
            $video = $video->join('recently_viewed_videos', 'videos.id', '=', 'recently_viewed_videos.video_id')->where('recently_viewed_videos.created_at', '>', Carbon::now()->subDays(30))->selectRaw('videos.*,count("video_id") as count')->groupBy('recently_viewed_videos.video_id')->where('is_live', '==', 0)->orderBy('count', 'desc')->take(10)->get();
            foreach ($video as $k => $v) {
                $video [$k] ['is_favourite'] = $v->authfavourites()->get()->count();
            }
        }
        return $video;
    }

    /**
     * function to get upcomming live videos
     *
     * @return mixed
     */
    public function getLiveVideos($live = '', $getCount = 9, $paginate = true)
    {
        if ($live) {
            $videos = $this->video->whereliveVideo()->orderBy('scheduledStartTime', 'asc')->get();
        } else {
            if ($this->request->header('x-request-type') == 'mobile') {
                $videos = $this->video->whereliveVideo()->orderBy('scheduledStartTime', 'asc')->take(5)->get();
            } else {
                $videos = $this->video->whereliveVideo()->orderBy('scheduledStartTime', 'asc');
                if ($paginate) {
                    $videos = $videos->paginate($getCount)->toArray();
                } else {
                    $videos = $videos->take($getCount)->get();
                }
            }
        }
        return $videos;
    }

    /**
     * function to get recent videos for video using slug
     *
     * @return array
     */
    public function getVideoBlockByType($type)
    {
        $perPage = config('access.perpage');
        $video = $this->video->whereCustomer();

        $fields = 'videos.id, videos.title, videos.slug, videos.description, videos.short_description, videos.thumbnail_image, videos.selected_thumb, videos.hls_playlist_url, count("video_id") as count, videos.id as is_favourite, videos.id as collection, videos.poster_image,videos.is_live';

        $userId = (auth()->user()) ? auth()->user()->id : 0;
        
        $inputArray = $this->request->all();
        if(isset($inputArray['order']) && !empty($inputArray['order'])) {
            $sort  = (!empty($inputArray['sort'])) ? $inputArray['sort'] : 'asc';
            $order = $inputArray['order'];
        }

        switch ($type) {
            case $type == 'banner':
                $video = $video->with(['categories.parent_category'])->leftJoin('favourite_videos as f1', function ($j) use ($userId) {
                    $j->on('videos.id', '=', 'f1.video_id')->on('f1.customer_id', '=', DB::raw($userId));
                })->selectRaw($fields)->where('is_live', '==', 0)->where('trailer_status', 1)->groupBy('videos.id')->orderBy('video_order', 'asc')->orderBy('id', 'desc')->paginate(5);
                $video = $video->toArray();
                $video['category_name'] = trans('general.banner_videos');
                break;
            case $type == 'recent':
                $video = $this->fetchRecentVideos($fields, $this->video);
                $video['category_name'] = trans('general.recent_videos');
                break;
            case $type == 'trending':
                $order = (!empty($order)) ? $order : 'count';
                $sort = (!empty($sort)) ? $sort : 'desc';
                $video = $video->with(['categories.parent_category'])->join('recently_viewed_videos', 'videos.id', '=', 'recently_viewed_videos.video_id')->where('recently_viewed_videos.created_at', '>', Carbon::now()->subDays(30))->selectRaw($fields)->where('is_live', '==', 0)->groupBy('recently_viewed_videos.video_id')->orderBy($order, $sort)->paginate($perPage);
                $video = $video->toArray();
                $video['category_name'] = trans('general.trending_videos');
                break;
            case $type == 'section_one':
                $nthCategory    = $this->getTopNthCategory();
                $video = $this->formatResponse($nthCategory, $fields, $video);
                break;
            case $type == 'section_two':
                $nthCategory    = $this->getTopNthCategory(1);
                $video = $this->formatResponse($nthCategory, $fields, $video);
                break;
            default:
                $video = $this->fetchNewVideos($fields, $video);
                $video = $video->toArray();
                $video['category_name'] = trans('general.new_videos');
                break;
        }

        return $video;
    }
    public function formatResponse($nthCategory, $fields, $video) {
        $categoryArray  = $this->fetchChildren($nthCategory);
        $video          = $this->fetchPopularVideos($fields, $video, $categoryArray);
        $video['category_name'] = (!empty($nthCategory)) ? 'Popular '. $nthCategory->title : '';
        $video['category_slug'] = (!empty($nthCategory)) ? $nthCategory->slug : '';
        return $video;

    }
    public function fetchChildren($category) {
        $categoryArray = [];
        $catId = !empty($category) ? $category['id'] : 0;
        $categoryInfo = $this->category->with(['child_category'])->where('id', $catId)->first();
        if(!empty($categoryInfo)) {
            if(isset($categoryInfo['child_category'])) {
                foreach($categoryInfo['child_category'] as $cat) {
                    $cat = $cat->makeVisible(['id']);
                    $categoryArray[$cat->id] = $cat->id;
                }
            }
            $categoryArray[] = $categoryInfo->id;
        }
        return $categoryArray;
    }

    public function fetchPopularVideos($fields, $video, $categoryArray) {
        return $video->with(['categories.parent_category'])->join('recently_viewed_videos', 'videos.id', '=', 'recently_viewed_videos.video_id')->join('video_categories', 'videos.id', '=', 'video_categories.video_id')->selectRaw($fields)->where('is_live', '==', 0)->whereIn('video_categories.category_id', $categoryArray)->groupBy('recently_viewed_videos.video_id')->orderBy('count', 'desc')->paginate(config('access.perpage'))->toArray();
    }

    /**
     * Function to fetch new videos
     * @param  [string] $fields - sql fields
     * @param  [object] $video - Video object
     * @return object
     */
    public function fetchNewVideos($fields, $video) {
        $order = 'id';
        $sort = 'desc';
        $inputArray = $this->request->all();
        if(isset($inputArray['order']) && !empty($inputArray['order'])) {
            $sort  = (!empty($inputArray['sort'])) ? $inputArray['sort'] : 'asc';
            $order = $inputArray['order'];
        }

        return $video->with(['categories.parent_category'])->leftJoin('recently_viewed_videos as f1', function ($j) {
                    $j->on('videos.id', '=', 'f1.video_id');
                })->selectRaw($fields)->where('is_live', '==', 0)->groupBy('videos.id')->orderBy($order, $sort)->paginate(config('access.perpage'));
    }

    /**
     * Function to fetch popular genre videos
     */
    public function fetchPopularGenre($paginate = true) {
        $this->setRules(['order' => 'sometimes|in:title', 'sort' => 'sometimes|in:asc,desc']);
        $this->validate($this->request, $this->getRules());

        $inputArray = $this->request->all();
        $collectionObj = new Group();
        $collection = $collectionObj
            ->join('collections_videos', 'collections_videos.group_id', '=', 'groups.id' )
            ->join('videos', 'videos.id', '=', 'collections_videos.video_id' )
            ->selectRaw('groups.*, count("collections_videos.id") as video_count')
            ->where('groups.is_active',1)
            ->where('videos.is_active',1)->where ( 'videos.job_status', 'Complete' )->where ( 'videos.is_archived', 0 )->whereIn ( 'videos.is_subscription', ((auth ()->user () && auth ()->user ()->isExpires ()) ? [ [ 0 ],[ 1 ] ] : [ 0 ]) );
        if(isset($inputArray['order']) && !empty($inputArray['order'])) {
          $sortName = ($inputArray['order'] == 'title') ? 'name' : $inputArray['order'];
          $collection = $collection->orderBy($sortName, $inputArray['sort']);
        }
        else {
          $collection = $collection->orderBy('video_count', 'desc');
        }

        $collection = $collection->groupBy('groups.id');
        $collection = ($paginate) ? $collection->paginate(config('access.perpage')) : $collection->get();
        $collection = $collection->toArray();
        $collection['category_name'] = trans('general.genre_videos');
        return $collection;
    }

    /**
     * Function to load more videos in homescreen
     */
    public function getMore() {
        $result['error']    = false;
        $result['message']  = '';
        $result['data']  = '';
        $this->setRules(['type' => 'required|in:new,recent,section_one,section_two,banner,trending,genre']);
        $this->validate($this->request, $this->getRules());
        try {
            if($this->request->type == 'genre') {
                $result['data'] = $this->fetchPopularGenre();
            }
            else {
               $result['data'] = $this->getVideoBlockByType($this->request->type);
            }
        }
        catch(\Exception $e) {
            $result['error']    = true;
            $result['message']    = $e->getMessage();
        }
        return $result;
    }

    
}