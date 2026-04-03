<?php

namespace Contus\Subscribers\Model;

use Contus\Base\Model;

class SubVideoOnDemand extends Model
{
    protected $table = "sub_video_on_demand";

    protected $fillable = [
        'subscriber_id',
        'video_on_demand_list',
        'is_active',
    ];
}
