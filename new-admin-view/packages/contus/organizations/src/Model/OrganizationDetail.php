<?php

namespace Contus\Organizations\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrganizationDetail extends Model {
    //
    use HasFactory;

    protected $table = 'organization_details';

    protected $fillable = [
        'organization_logo',
        'organization_name',
        'prefix',
        'select_platform',
        'api_access',
        'login_token',
        'api_token',
        'max_activation_length',
        'device_activation_limit',
        'void_payment_in',
        'custom_charges',
        'custom_subscription',
        'device_slots',
        'device_linking',
        'link_code_expiration',
        'active_toa',
        'subscription_activation',
        'subscription_prorating',
        'content_add_on_prorating',
        'voucher_subscribers',
        'expired_voucher_removal',
        'voucher_slots',
        'organization_id',
        'unlimited',
        'use_system_default',
        'disallow_void',
    ];

    public function organization() {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    // public function user() {
    //     return $this->belongsTo(User::class, 'user_id', 'id');
    // }
}
