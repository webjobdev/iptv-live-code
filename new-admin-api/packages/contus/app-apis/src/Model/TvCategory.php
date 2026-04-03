<?php

namespace Contus\AppApi\Models;

use Contus\Base\Model;
use Contus\Channel\Model\Channel;
use Contus\Organizations\Model\Organization;
use Contus\Organizations\Model\OrganizationDetail;
use Contus\Video\Models\CategoryChannelList;

class TvCategory extends Model
{
    protected $table = 'tv_category';

    protected $fillable = [
        'organization',
        'channel_id',
        'sub_category_id',
        'tv_categorie_name',
        'category_name',
        'categorie_id',
        'category_order'
    ];


    /* Organization */
    // public function getOrganization()
    // {
    //     return $this->belongsTo(Organization::class, 'organization', 'id');
    // }

    // /* Level 1 → Child Category (categorie_id) */
    // public function categorie_id()
    // {
    //     return $this->hasMany(TvCategory::class, 'categorie_id', 'id');
    // }

    // /* Level 2 → Sub Category (sub_category_id) */
    // public function get_sub_category()
    // {
    //     return $this->hasMany(TvCategory::class, 'sub_category_id', 'id');
    // }

    // /* Level 3 → Channel */
    // public function getChannel()
    // {
    //     return $this->belongsTo(Channel::class, 'channel_id', 'id');
    // }


    // for mobile app nested category fetch

    public function getChannel()
    {
        return $this->belongsTo(CategoryChannelList::class, 'channel', 'id');
    }

    public function children()
    {
        return $this->hasMany(TvCategory::class, 'categorie_id', 'id');
    }


    public function categorie_id()
    {
        return $this->hasMany(TvCategory::class, 'categorie_id', 'id');
    }
}
