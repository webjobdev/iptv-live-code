<?php

namespace Contus\Tvshow\Model;

use Contus\AppApi\Model\ContinueWatching;
use Contus\Base\Model;
use Contus\Drm\Model\DrmProfileDetails;
use Contus\Organizations\Model\Organization;
use Contus\Organizations\Model\TvShowContent;
use Contus\Settings\Model\PlayBack;
use Contus\StreamServices\Model\StreamingUrlPolicy;

class SeasonEpisode extends Model
{
    protected $table = 'tvshow_season_episodes';

    protected $fillable = [
        'tv_show_id',
        'season_id',
        'poster_image',
        'thumbnail_image',
        'episode_name',
        'episode_number',
        'streaming_url',
        'description',
        'directors',
        'presenter',
        'resolution',
        'length',
        'content_sets',
        'release_date',
        'scheduled_time',
        'expire_scheduled_time',
        'publish_date',
        'drm_type',
        'drm_profile',
        'policy',
        'playback_token',
        'views',
        'scheduled_publishing',
        'publish_now',
        'is_active',
    ];

    public function GetTvShow()
    {
        $tvshow = $this->belongsTo(TvShow::class, 'tv_show_id', 'id');
        return $tvshow;
    }

    public function getAllOrganization()
    {
        return $this->belongsToMany(Organization::class, 'tv_show_season_episode_organization', 'tv_show_season_episode_id', 'organization_id');
    }


    public function GetSeason()
    {
        $season = $this->belongsTo(TvShowSeason::class, 'season_id', 'id');
        return $season;
    }

    public function DrmProfile()
    {
        $drm = $this->belongsTo(DrmProfileDetails::class, 'drm_profile', 'id');
        return $drm;
    }

    protected $appends = ['channel_sets'];

    public function getChannelSetsAttribute()
    {
        $contentSets = json_decode($this->content_sets, true);

        // Safety check
        if (empty($contentSets) || !is_array($contentSets)) {
            return [];
        }

        // Loop through each organization group
        return collect($contentSets)->map(function ($org) {
            // Get all content set IDs for this organization
            $ids = $org['channel_contentset'] ?? [];

            // Fetch actual records from DB (only id, name or any other field)
            $records = TvShowContent::whereIn('id', $ids)
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

    public function ContinueWatching()
    {
        return $this->belongsTo(ContinueWatching::class, 'id', 'watchable_id')->where('watching_type', 'episode');
    }
}