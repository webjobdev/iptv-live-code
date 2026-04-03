<?php

namespace Contus\ChannelServices\Model;

use Contus\Base\Model;

class EpgServiceExecution extends Model
{
    protected $table = 'epg_service_executions';

    protected $fillable = [
        'epg_service_id',
        'status',
        'completed_programmes',
        'fail_reason',
        'start_time',
        'finish_time',
        'executed_by',
    ];

    public function epgService()
    {
        return $this->belongsTo(EpgService::class, 'epg_service_id', 'id');
    }
}
