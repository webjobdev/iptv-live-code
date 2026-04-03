<?php

namespace Contus\Subscribers\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubscriberAssignedDevice extends Model
{
    use HasFactory;

    protected $table = 'subscriber_assigned_device';

    protected $fillable = [
        'subscriber_id',
        'device_id',
        'subscription_and_payments_id',
        'price',
        'device_name',
        'deletable',
        'is_primary',
    ];

    public function device_detaile()
    {
        return $this->belongsTo(OrgDevices::class, 'device_id');
    }

    public function subscriber_detaile()
    {
        return $this->belongsTo(OrgSubscribers::class, 'subscriber_id');
    }

    public function subscription_and_payments_detaile()
    {
        return $this->belongsTo(OrgSubscriberAndPayment::class, 'subscription_and_payments_id');
    }
}
