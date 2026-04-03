<?php

namespace Contus\Video\Models;

use Contus\Base\Model;
use Contus\Channel\Model\Channel;

class SubVodCategory extends Model
{

    protected $table = 'vod_category_sub_category';

    protected $fillable = [
        'categorie_id',
        'sub_category_name',
        'category_order'
    ];

    // public function categoryDetail()
    // {
    //     return $this->belongsTo(VodCategory::class, 'categorie_id', 'id');
    // }

    // public function category()
    // {
    //     return $this->belongsTo(TvCategory::class, 'categorie_id', 'id');
    // }

}