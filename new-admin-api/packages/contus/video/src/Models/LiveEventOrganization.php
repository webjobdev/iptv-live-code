<?php

namespace Contus\Video\Models;

use Contus\Base\Model;
use Contus\Organizations\Model\Organization;

class LiveEventOrganization extends Model
{

    protected $table = 'live_event_organization';

    protected $fillable = [
        'live_event_id',
        'organization_id',
        'created_by'
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

}