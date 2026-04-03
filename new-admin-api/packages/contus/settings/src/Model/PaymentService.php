<?php

namespace Contus\Settings\Model;

use Contus\Base\Model;
use Contus\Organizations\Model\OrganizationDetail;
use Contus\Organizations\Model\OrganizationPaymentService;

class PaymentService extends Model
{
    protected $table = 'payment_services';

    protected $fillable = [
        'payment_provider',
        'provider_data',
        'is_active',
        'default',
    ];

    protected $casts = [
        'provider_data' => 'array'
    ];

    public function SystemDefault()
    {
        return $this->hasMany(OrganizationDetail::class, 'payment_service_system_default', 'id');
    }

    public function organizationDefault()
    {
        return $this->hasMany(OrganizationPaymentService::class, 'payment_service_id', 'id');
    }
}
