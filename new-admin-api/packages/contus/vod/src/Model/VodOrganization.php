<?php

namespace Contus\Vod\Model;

use Contus\Base\Model;
use Contus\Organizations\Model\Organization;

class VodOrganization extends Model
{

    protected $table = 'vod_organization';

    protected $fillable = [
        'vod_id',
        'organization_id',
        'created_by'
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

}
