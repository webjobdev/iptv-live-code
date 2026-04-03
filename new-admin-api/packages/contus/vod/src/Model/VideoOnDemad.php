<?php

namespace Contus\Vod\Model;

use Contus\Base\Model;
use Contus\Organizations\Model\Organization;
use Contus\Organizations\Model\OrganizationDetail;
use Contus\Organizations\Model\VodContent;
use Contus\Settings\Model\PlayBack;
use Contus\StreamServices\Model\StreamingUrlPolicy;
use Contus\Video\Models\VodCategory;
use Illuminate\Support\Facades\DB;
use Contus\AppApi\Model\ContinueWatching;

class VideoOnDemad extends Model
{

    protected $table = 'video_on_demand';

    protected $fillable = [
        'organization',
        'drm_type',
        'drm_profile',
        'poster_image',
        'thumbnail_image',
        'title',
        'description',
        'release_year',
        'directors',
        'presenter',
        'timeParts',
        'scheduled_time',
        'expire_scheduled_time',
        'publish_date',
        'video_quality',
        'streaming_url',
        'trailer_url',
        'playback_token',
        'policy',
        'content_sets',
        'category',
        'age_rating',
        'age_limit',
        'is_parental',
        'scheduled_publishing',
        'publish_now',
        'geo_policy',
        'is_active',
        'geo_block_country_list',
        'year'
    ];

    protected $appends = ['channel_sets', 'subscriber_count'];
    protected $casts = [
        'timeParts' => 'array',
        'category' => 'array',
        'geo_block_country_list' => 'array',
    ];

    public function getChannelSetsAttribute()
    {
        $contentSets = json_decode($this->content_sets, true);

        if (empty($contentSets) || !is_array($contentSets)) {
            return [];
        }

        return collect($contentSets)->map(function ($org) {
            // Get all content set IDs for this organization
            $ids = $org['vod_contentset'] ?? [];

            // Fetch actual records from DB (only id, name or any other field)
            $records = VodContent::whereIn('id', $ids)
                ->get()
                ->each(function ($model) {
                    // Disable appended attributes temporarily
                    $model->setAppends([]);
                });

            return $records;
        })->flatten(1)->values()->toArray();
    }


    public function GetPolicy()
    {
        return $this->belongsTo(StreamingUrlPolicy::class, 'policy', 'id');
        // return $this;
    }

    public function GetPlayback_token()
    {
        return $this->belongsTo(PlayBack::class, 'playback_token', 'id');
        // return $this;
    }

    public function getAllOrganization()
    {
        return $this->belongsToMany(Organization::class, 'vod_organization', 'vod_id', 'organization_id');
    }

    // get subscriber count of channel
    public function getSubscriberCountAttribute()
    {
        return DB::table('sub_video_on_demand')
            ->where('is_active', 1)
            // match current channel
            ->where('video_on_demand_list', $this->title)
            // count unique subscribers only
            ->distinct('subscriber_id')
            ->count('subscriber_id');
    }

    public function getOrganization()
    {
        return $this->belongsTo(OrganizationDetail::class, 'organization', 'id');
    }

    public function ContinueWatching()
    {
        return $this->belongsTo(ContinueWatching::class, 'id', 'watchable_id')->where('watching_type', 'movie');
    }

}
