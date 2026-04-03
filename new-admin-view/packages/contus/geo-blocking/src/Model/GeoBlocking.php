<?php

namespace Contus\ApiAccess\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeoBlocking extends Model {
    use HasFactory;

    protected $table = 'geo_blocking';

    protected $fillable = ['name', 'type', 'geo_ip_status', 'geo_protection_status', 'countries', 'override_geo_restrictions'];

}
