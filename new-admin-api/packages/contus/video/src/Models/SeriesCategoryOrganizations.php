<?php

namespace Contus\Video\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeriesCategoryOrganizations extends Model
{
    use HasFactory;

    protected $table = 'series_category_organizations';

    protected $fillable = [
        'series_category_id',
        'organization_id',
        'created_by',
    ];
}
