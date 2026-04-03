<?php

namespace Contus\Settings\Model;

use Contus\Base\Model;
use Contus\Organizations\Model\OrganizationDetail;

class PaymentServiceCurrencyConverter extends Model
{
    protected $table = 'currency_converter';

    protected $fillable = [
        'token',
        'refresh_rate_mode',
        'refresh_rate',
        'refresh_rate_unit',
    ];

    public function organizationDetails()
    {
        return $this->hasMany(OrganizationDetail::class, 'currency_converter_system_default', 'id');
    }
}
