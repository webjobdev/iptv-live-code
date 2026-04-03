<?php

namespace Contus\Video\Models;

use Contus\Base\Model;
use Contus\Organizations\Model\Organization;

class SeriesCategory extends Model
{
    protected $table = 'series_category';

    protected $fillable = [
        'organization',
        'sub_category_id',
        'series_categorie_name',
        'category_name',
        'categorie_id',
        'category_order'
    ];

    public function getOrganization()
    {
        return $this->belongsToMany(Organization::class, 'series_category_organizations', 'series_category_id', 'organization_id');
    }

    public function getSubCategory()
    {
        return $this->hasMany(SeriesCategory::class, 'sub_category_id', 'id');
    }

    public function categories()
    {
        return $this->hasMany(SeriesCategory::class, 'categorie_id', 'id');
    }
}
