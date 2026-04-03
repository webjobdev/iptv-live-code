<?php

namespace Contus\BulkUpload\Model;

use Contus\Base\Model;
use Contus\Channel\Model\Channel;


class M3UChannel extends Model
{

    protected $table = 'm3u_channel';

    protected $fillable = [
        'channel_id',
        'm3u_url'
    ];

    public function getChannel()
    {
        return $this->belongsTo(Channel::class, 'channel_id');
    }
}