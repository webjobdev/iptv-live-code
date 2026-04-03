<?php

namespace Contus\Settings\Model;

use Contus\Base\Model;

class PlayBack extends Model
{
    protected $table = 'play_back_token';

    protected $fillable = [
        'name',
        'type',
        'secret_key',
        'token_time',
        'secret_generation_number',
        'ignore_device_ip_verification',
        'rsa_private_key',
        'url_format',
        'is_active',
    ];

}
