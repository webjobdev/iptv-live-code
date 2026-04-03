<?php

namespace Contus\Settings\Model;

use Contus\Base\Model;
use Contus\Organizations\Model\OrganizationCurrencies;

class PaymentServiceCurrency extends Model
{
    protected $table = 'payment_currencie';

    protected $fillable = [
        'currency_code',
        'currency_symbol',
        'position',
        'sample',
    ];

    public function organizationCurrencies()
    {
        return $this->hasMany(OrganizationCurrencies::class, 'currency_id', 'id');
    }

}
