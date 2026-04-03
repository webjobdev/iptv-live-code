<?php

namespace Contus\Tvshow\Model;

use Contus\Base\Model;
use Contus\Organizations\Model\Organization;
use Contus\Organizations\Model\OrganizationDetail;
use Contus\Organizations\Model\TvShowContent;
use Contus\Settings\Model\PlayBack;
use Contus\StreamServices\Model\StreamingUrlPolicy;

class TvShow extends Model
{
    protected $table = 'tv_shows';
    protected $fillable = [
        'organization',
        'poster_image',
        'thumbnail_image',
        'title',
        'description',
        'release_year',
        'directors',
        'presenter',
        'scheduled_time',
        'expire_scheduled_time',
        'publish_date',
        'age_rating',
        'age_limit',
        'is_parental',
        'content_sets',
        'category',
        'trailer_url',
        'playback_token',
        'policy',
        'scheduled_publishing',
        'publish_now',
        'geo_policy',
        'geo_block_country_list',
        'is_active',
    ];

    // protected $appends = [];
    // protected $with = ['getSeasons.getEpisodes'];

    protected $casts = [
        'category' => 'array',
    ];

    public function getSeasons()
    {
        return $this->hasMany(TvShowSeason::class, 'tv_show_id');
    }

    public function getAllOrganization()
    {
        return $this->belongsToMany(Organization::class, 'tv_show_organization', 'tv_show_id', 'organization_id');
    }

    public function GetSeasonData()
    {
        return $this->hasMany(TvShowSeason::class, 'tv_show_id');
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
            $ids = $org['tv_show_contentset'] ?? [];

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

    public function getOrganization()
    {
        return $this->belongsTo(OrganizationDetail::class, 'organization', 'id');
    }
}
