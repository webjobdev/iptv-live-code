<?php

namespace Contus\Subscribers\Model;

use Contus\Base\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrgCreditCard extends Model {
    use HasFactory;

    protected $table = 'org_subscriber_creditcard';

    protected $fillable = [
        'subscriber_id',
        'profile_name',
        'security_type',
        'card_number',
        'expiration_month',
        'expiration_year',
        'billing_address',
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'address',
        'city',
        'zip_code',
        'country',
        'state',
    ];
}
