<?php

namespace Contus\Video\Models;

use Contus\Base\Model;
use Contus\Channel\Model\Channel;
use Contus\Organizations\Model\OrganizationDetail;

class CategoryChannelList extends Model
{
    protected $table = 'tv_category_channel_list';

    protected $fillable = [
        'channel_id',
        'categorie_id',
    ];

    public function channelDetail()
    {
        return $this->belongsTo(Channel::class, 'channel_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo(TvCategory::class, 'categorie_id', 'id');
    }
}