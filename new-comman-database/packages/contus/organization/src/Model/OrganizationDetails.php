<?php

namespace Contus\Organization\Model;

use Contus\Base\Model as BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganizationDetails extends BaseModel
{
    use HasFactory;

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
        'max_activation_length_system_default',
        'device_activation_limit_system_default',
        'void_payment_in_system_default',
        'custom_charges_system_default',
        'custom_subscription_system_default',
        'device_slots_system_default',
        'device_linking_system_default',
        'link_code_expiration_system_default',
        'active_toa_system_default',
        'subscription_activation_system_default',
        'subscription_prorating_system_default',
        'content_add_on_prorating_system_default',
        'voucher_subscribers_system_default',
        'expired_voucher_removal_system_default',
        'voucher_slots_system_default',
    ];
}
