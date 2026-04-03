<?php

namespace Contus\BulkUpload\Model;

use Contus\Base\Model;
use Contus\Channel\Model\Channel;
use Contus\Tvshow\Model\TvShow;


class M3UTvShow extends Model
{

    protected $table = 'm3u_tvshow';

    protected $fillable = [
        'tv_show_id',
        'm3u_url'
    ];

    public function getTvShow()
    {
        return $this->belongsTo(TvShow::class, 'tv_show_id');
    }
}