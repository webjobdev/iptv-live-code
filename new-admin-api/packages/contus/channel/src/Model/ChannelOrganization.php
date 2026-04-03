<?php

namespace Contus\Channel\Model;

use Contus\Base\Model;
use Contus\Organizations\Model\Organization;

class ChannelOrganization extends Model
{
    protected $table = 'channel_organization';

    protected $fillable = [
        'channel_id',
        'organization_id',
        'created_by'
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

}
