<?php

namespace Contus\Video\Models;

use Contus\Base\Model;
use Contus\Organizations\Model\Organization;
use Contus\Organizations\Model\OrganizationDetail;

class VodCategory extends Model
{
    protected $table = 'vod_category';

    protected $fillable = [
        'organization',
        'sub_category_id',
        'category_name',
        'categorie_id',
        'vod_categorie_name',
        'category_order'
    ];

    public function getOrganization()
    {
        return $this->belongsToMany(Organization::class, 'vod_category_organizations', 'vod_category_id', 'organization_id');
    }

    public function getSubCategory()
    {
        return $this->hasMany(VodCategory::class, 'sub_category_id', 'id');
    }

    public function categories()
    {
        return $this->hasMany(VodCategory::class, 'categorie_id', 'id');
    }
}
