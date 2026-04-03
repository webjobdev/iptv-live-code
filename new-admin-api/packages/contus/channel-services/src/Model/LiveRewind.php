<?php

namespace Contus\ChannelServices\Model;

use Contus\Base\Model;
use Contus\Channel\Model\Channel;

class LiveRewind extends Model
{

    protected $table = 'live_rewind_channel_service';


    protected $fillable = [
        'channel_id',
        'drm_type',
        'drm_profile',
        'live_rewind_node',
        'streaming_provider',
        'custome_streaming_url',
        'url',
        'playback_token',
        'token_generator',
        'is_active',
    ];

    public function getChannel()
    {
        $channel = $this->belongsTo(Channel::class, 'channel_id', 'id');
        return $channel;
    }

    // function boot(){
    //     self::creating($abs) 
    //     {
    //         dd($this->$table);
    //     }
    // } 

}