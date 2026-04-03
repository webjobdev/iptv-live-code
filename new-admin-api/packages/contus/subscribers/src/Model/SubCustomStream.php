<?php

namespace Contus\Subscribers\Model;

use Contus\Base\Model;

class SubCustomStream extends Model
{
    protected $table = "sub_custom_channel_list";

    protected $fillable = [
        'subscriber_id',
        'channel_list',
        'is_active',
    ];

    public function subscribers()
    {
        return $this->belongsTo(OrgSubscribers::class, 'subscriber_id', 'id');
    }
}
