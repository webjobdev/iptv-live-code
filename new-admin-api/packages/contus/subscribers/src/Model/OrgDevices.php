<?php

namespace Contus\Subscribers\Model;

use Contus\Organizations\Model\Organization;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrgDevices extends Model
{
    use HasFactory;

    protected $table = 'org_subscriber_devices';

    protected $fillable = [
        'brand_model',
        'subscriber_id',
        'device_type',
        'mac_address',
        'serial_number',
        'identifier',
        'ip_address',
        'location',
        // 'country',
        // 'latitude',
        // 'longitude',
        'last_session',
        'is_active',
        'isp',
        'firmware_version',
        'timezone',
        'security_code_required',
        'security_code',
        'assigned_subscribers',
        'create_subscriber',
        'list',
        'parse_file',
        'first_value',
        'serial_mac_seperator',
        'device_redirect'
    ];

    protected $casts = [
        'assigned_subscribers' => 'array',
    ];

    public function SubscriberData()
    {
        return $this->belongsTo(OrgSubscribers::class, 'subscriber_id', 'id');
    }

    // this relationship for dashboard
    public function deviceCount()
    {
        return OrgSubscribers::withCount('devices')->get();
    }

    public function getAllOrganization()
    {
        return $this->belongsToMany(Organization::class, 'subscriber_device_organizations', 'subscriber_device_id', 'organization_id');
    }

    public function subscriber_assigned_device()
    {
        return $this->hasOne(SubscriberAssignedDevice::class, 'device_id');
    }
}
