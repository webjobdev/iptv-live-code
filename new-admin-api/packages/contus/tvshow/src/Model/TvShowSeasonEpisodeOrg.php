<?php

namespace Contus\Tvshow\Model;

use Contus\Base\Model;
use Contus\Organizations\Model\Organization;

class TvShowSeasonEpisodeOrg extends Model
{

    protected $table = 'tv_show_season_episode_organization';

    protected $fillable = [
        'tv_show_season_episode_id',
        'organization_id',
        'created_by'
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

}