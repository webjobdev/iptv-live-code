<?php

namespace Contus\ChannelServices\Model;

use Contus\Base\Model;
use Contus\Channel\Model\Channel;

class EpgProgram extends Model
{
    protected $table = 'epg_programs';

    protected $fillable = [
        'channel_id',
        'epg_service_id',
        'epg_id',
        'title',
        'description',
        'start_date_time',
        'end_date_time',
        'category',
        'image',
    ];

    public function channel()
    {
        return $this->belongsTo(Channel::class, 'channel_id', 'id');
    }

    public function epgService()
    {
        return $this->belongsTo(EpgService::class, 'epg_service_id', 'id');
    }
}
