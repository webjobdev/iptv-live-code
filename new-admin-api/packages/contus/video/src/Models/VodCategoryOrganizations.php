<?php

namespace Contus\Video\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VodCategoryOrganizations extends Model
{
    use HasFactory;

    protected $table = 'vod_category_organizations';

    protected $fillable = [
        'vod_category_id',
        'organization_id',
        'created_by',
    ];
}
