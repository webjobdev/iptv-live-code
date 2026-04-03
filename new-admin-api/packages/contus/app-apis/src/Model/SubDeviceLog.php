<?php

namespace Contus\AppApi\Model;

use Contus\Base\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubDeviceLog extends Model
{

    use HasFactory;

    protected $table = 'subscriber_device_logs';

    protected $fillable = [
        'subscriber_id',
        'device_id',
        'plan_id',
        'login_time',
        'ip_address',
    ];
}
