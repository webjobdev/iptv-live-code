<?php

namespace Contus\Subscribers\Model;

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
        'city',
        'country',
        'latitude',
        'longitude',
        'last_session',
        'status',
    ];

    public function SubscriberData()
    {
        return $this->belongsTo(OrgSubscribers::class, 'subscriber_id', 'id');
    }
}
