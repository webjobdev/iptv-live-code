<?php

namespace Contus\Video\Models;

use Contus\Base\Model;
use Contus\Channel\Model\Channel;

class SubSeriesCategory extends Model
{
    protected $table = 'series_category_sub_category';

    protected $fillable = [
        'categorie_id',
        'sub_category_name',
        'category_order'
    ];

    //     public function channelDetail()
    // {
    //     return $this->belongsTo(Channel::class, 'channel_id', 'id');
    // }

    // public function category()
    // {
    //     return $this->belongsTo(TvCategory::class, 'categorie_id', 'id');
    // }
}