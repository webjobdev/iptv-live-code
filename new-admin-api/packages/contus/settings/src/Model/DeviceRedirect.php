<?php

namespace Contus\Settings\Model;

use Contus\Base\Model;

class DeviceRedirect extends Model
{
    protected $table = 'device_redirect';

    protected $fillable = [
        'name',
        'url',
        'is_active',
    ];

}
