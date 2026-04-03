<?php

namespace Contus\Video\Models;

use Contus\Base\Model;
use Contus\Channel\Model\Channel;
use Contus\Organizations\Model\Organization;
use Contus\Organizations\Model\OrganizationDetail;

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
    public function getOrganization()
    {
        return $this->belongsToMany(Organization::class, 'tv_category_organizations', 'tv_category_id', 'organization_id');
    }

    /* Level 1 → Child Category (categorie_id) */
    public function categorie_id()
    {
        return $this->hasMany(TvCategory::class, 'categorie_id', 'id');
    }

    /* Level 2 → Sub Category (sub_category_id) */
    public function get_sub_category()
    {
        return $this->hasMany(TvCategory::class, 'sub_category_id', 'id');
    }

    /* Level 3 → Channel */
    public function getChannel()
    {
        return $this->belongsTo(Channel::class, 'channel_id', 'id');
    }


    // for mobile app nested category fetch
    public function children()
    {
        return $this->hasMany(TvCategory::class, 'categorie_id', 'id');
    }

    public function subCategories()
    {
        return $this->hasMany(self::class, 'sub_category_id', 'id');
    }

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($tvCategory) {
            $tvCategory->getOrganization()->detach();
        });
    }
}
