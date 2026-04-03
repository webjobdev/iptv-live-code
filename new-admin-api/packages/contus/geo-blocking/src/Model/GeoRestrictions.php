<?php

namespace Contus\GeoBlocking\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeoRestrictions extends Model {
    use HasFactory;

    protected $table = 'geo_restrictions';

    protected $fillable = ['name', 'type', 'geo_ip_status', 'geo_protection_status', 'countries', 'override_geo_restrictions'];

    protected $casts = ['countries' => 'array'];
}
