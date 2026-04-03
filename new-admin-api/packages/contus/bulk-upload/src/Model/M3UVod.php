<?php

namespace Contus\BulkUpload\Model;

use Contus\Base\Model;
use Contus\Vod\Model\VideoOnDemad;


class M3UVod extends Model
{

    protected $table = 'm3u_vod';

    protected $fillable = [
        'vod_id',
        'm3u_url'
    ];

    public function getVod()
    {
        return $this->belongsTo(VideoOnDemad::class, 'vod_id');
    }
}