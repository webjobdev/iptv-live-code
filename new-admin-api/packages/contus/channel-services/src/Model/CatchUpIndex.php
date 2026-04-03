<?php

namespace Contus\ChannelServices\Model;

use Contus\Base\Model;
use Contus\Channel\Model\Channel;

class CatchUpIndex extends Model
{

    protected $table = 'catch_up_channel_service';

    protected $fillable = [
        'channel_id',
        'drm_type',
        'drm_profile',
        'description',
        'days',
        'schedule_base',
        'streaming_provider',
        'custom_streaming_url',
        'url',
        'playback_token',
        'token_generator',
        'is_active',
    ];

    public function GetChannel(){
        return $this->belongsTo(Channel::class, 'channel_id', 'id');
    }

}