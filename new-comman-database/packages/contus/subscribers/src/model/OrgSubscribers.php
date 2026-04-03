<?php

namespace Contus\Subscribers\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrgSubscribers extends Model
{
    use HasFactory;

    protected $table = 'org_subscribers';

    protected $fillable = [
        'organization_id',
        'organization_name',
        'account_number',
        'pin_code',
        'user_name',
        'password',
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'address',
        'city',
        'zip_code',
        'country',
        'state',
        'language',
        'date_of_birth',
        'timezone',
    ];
}
