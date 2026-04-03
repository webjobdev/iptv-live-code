<?php 

namespace Contus\Subscribers\Model;

use Contus\Base\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrgDevices extends Model{
    use HasFactory;

    protected $fillable = [
        'brand_model',
        'mac_address',
        'serial_number',
        'identifier',
        'ip_address',
        'city',
        'country',
        'latitude',
        'longitude',
        'last_session',
        'status',
    ];
}