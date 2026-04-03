<?php

namespace Contus\Devices\Model;

use Contus\Organizations\Model\Organization;
use Illuminate\Database\Eloquent\Model;

class DeviceOraganization extends Model
{
    protected $table = 'subscriber_device_organizations';

    protected $fillable = [
        'subscriber_device_id',
        'organization_id',
        'create_by'
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id', 'id');
    }
}