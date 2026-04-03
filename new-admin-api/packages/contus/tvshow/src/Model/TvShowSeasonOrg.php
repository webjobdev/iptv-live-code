<?php

namespace Contus\Tvshow\Model;

use Contus\Base\Model;
use Contus\Organizations\Model\Organization;

class TvShowSeasonOrg extends Model
{

    protected $table = 'tv_show_season_organization';

    protected $fillable = [
        'tv_show_season_id',
        'organization_id',
        'created_by'
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

}