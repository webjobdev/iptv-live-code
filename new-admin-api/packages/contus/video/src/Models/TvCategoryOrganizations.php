<?php

namespace Contus\Video\Models;

use Illuminate\Database\Eloquent\Model;

class TvCategoryOrganizations extends Model
{
    protected $table = 'tv_category_organizations';
    protected $fillable = [
        'tv_category_id',
        'organization_id',
        'created_by'
    ];
}