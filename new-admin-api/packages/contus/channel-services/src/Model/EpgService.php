<?php

namespace Contus\ChannelServices\Model;

use Contus\Base\Model;

class EpgService extends Model
{

    protected $table = 'epg_service_channel_service';

    protected $fillable = [
        'task_name',
        'schedule_base',
        'start_time',
        'time_zone',
        'shift_postfix',
        'source_url',
        'last_run',
        'next_run',
        'is_active',
    ];


    protected $casts = [
        'start_time' => 'array',
    ];

    public function executions()
    {
        return $this->hasMany(EpgServiceExecution::class, 'epg_service_id', 'id');
    }

    public function programs()
    {
        return $this->hasMany(EpgProgram::class, 'epg_service_id', 'id');
    }
}