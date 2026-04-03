<?php

namespace Contus\Channel\Model;

use Contus\Base\Model;
use Contus\ChannelServices\Model\CatchUpIndex;
use Contus\Drm\Model\DrmProfileDetails;
use Contus\Organizations\Model\ChannelContet;
use Contus\Organizations\Model\OrganizationDetail;
use Contus\Organizations\Model\Organization;
use Contus\Settings\Model\PlayBack;
use Contus\StreamServices\Model\StreamingUrlPolicy;
use Contus\Subscribers\Model\SubCustomStream;
use Contus\Video\Models\SeriesCategory;
use Contus\Video\Models\VodCategory;
use Illuminate\Support\Facades\DB;

class Channel extends Model
{
    protected $table = 'channels';

    protected $fillable = [
        'channel_name',
        'poster_image',
        'sorting_number',
        'language',
        'video_quality',
        'streaming_url',
        'policy',
        'playback_token',
        'epg_id',
        'content_sets',
        'drm_type',
        'drm_profile',
        'organization',
        'age_rating(pin_locked)',
        'geo_blocking',
        'group_chat',
        'geo_block_country_list',
        'is_active'
    ];

    public function getOrganization()
    {
        return $this->belongsTo(OrganizationDetail::class, 'organization', 'id');
    }

    public function getAllOrganization()
    {
        return $this->belongsToMany(Organization::class, 'channel_organization', 'channel_id', 'organization_id');
    }

    public function getDrm()
    {
        return $this->belongsTo(DrmProfileDetails::class, 'drm_profile', 'id');
    }

    protected $casts = [
        'geo_block_country_list' => 'array',
        // 'content_sets' => 'array',
    ];

    protected $appends = ['channel_sets', 'subscriber_count'];

    public function getChannelSetsAttribute()
    {
        $contentSets = json_decode($this->content_sets, true);

        // Safety check
        if (empty($contentSets) || !is_array($contentSets)) {
            return [];
        }

        return collect($contentSets)->map(function ($org) {
            $ids = $org['channel_contentset'] ?? [];
            $records = ChannelContet::whereIn('id', $ids)
                ->get()
                ->each(function ($model) {
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

    public function catchUpTvChannels()
    {
        return $this->hasMany(CatchUpIndex::class);
    }

    // get subscriber count of channel
    public function getSubscriberCountAttribute()
    {
        return DB::table('sub_custom_channel_list')
            ->where('is_active', 1)
            // match current channel
            ->where('channel_list', $this->channel_name)
            // count unique subscribers only
            ->distinct('subscriber_id')
            ->count('subscriber_id');
    }


}
