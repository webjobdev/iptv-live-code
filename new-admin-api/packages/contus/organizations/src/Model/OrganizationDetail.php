<?php

namespace Contus\Organizations\Model;

use Contus\Settings\Model\PaymentService;
use Contus\Settings\Model\PaymentServiceCurrencyConverter;
use Contus\Subscribers\Model\OrgSubscribers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrganizationDetail extends Model
{
    use HasFactory;

    protected $table = 'organization_details';

    protected $fillable = [
        'provider_id',
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

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id', 'id');
    }

    public function currencyConverter()
    {
        return $this->belongsTo(PaymentServiceCurrencyConverter::class, 'currency_converter_system_default', 'id');
    }

    public function subscribers()
    {
        return $this->hasMany(OrgSubscribers::class, 'organization_id', 'id');
    }

    public function channels()
    {
        return $this->hasMany(ChannelContet::class, 'organization_id', 'id');
    }

    public function vods()
    {
        return $this->hasMany(VodContent::class, 'organization_id', 'id');
    }

    public function defaultPymentList()
    {
        return $this->belongsTo(PaymentService::class, 'payment_service_default', 'id');
    }

    public function OrgMonPlan()
    {
        return $this->hasMany(OrgMonetizationPlanss::class, 'organization_id', 'id');
    }
}
