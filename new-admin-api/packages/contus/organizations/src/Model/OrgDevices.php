<?php

namespace Contus\Organizations\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrgDevices extends Model {
    use HasFactory;

    protected $table = 'org_user_devices';

    protected $fillable = [
        'brand_model',
        'mac_address',
        'serial_number',
        'identifier',
        'ip_address',
        'location',
        'last_session',
        'status',
    ];
}
