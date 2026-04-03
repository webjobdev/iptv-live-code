<?php

namespace Contus\GeoBlocking\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IpRestrictions extends Model
{
    use HasFactory;

    protected $table = 'ip_restrictions';

    protected $fillable = ['mode', 'ip_address', 'geo_ip_status'];

    protected $casts = ['ip_address' => 'array'];
}
