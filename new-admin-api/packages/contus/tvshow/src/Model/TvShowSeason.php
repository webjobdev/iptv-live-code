<?php

namespace Contus\Tvshow\Model;

use Contus\Base\Model;
use Contus\Organizations\Model\Organization;
use Contus\Organizations\Model\TvShowContent;

class TvShowSeason extends Model
{
    protected $table = "tv_show_seasons";

    protected $fillable = [
        'tv_show_id',
        'poster_image',
        'thumbnail_image',
        'title',
        'season_number',
        'description',
        'directors',
        'presenter',
        'release_date',
        'scheduled_time',
        'content_sets',
        'expire_scheduled_time',
        'expire_time_unlimited',
        'publish_date',
        'scheduled_publishing',
        'publish_now',
        'is_active',
    ];

    public function GetTvshow()
    {
        $tvshow = $this->belongsTo(TvShow::class, 'tv_show_id', 'id');
        return $tvshow;
    }

    public function getEpisodes()
    {
        return $this->hasMany(SeasonEpisode::class, 'season_id');
    }

    public function getAllOrganization()
    {
        return $this->belongsToMany(Organization::class, 'tv_show_season_organization', 'tv_show_season_id', 'organization_id');
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
}