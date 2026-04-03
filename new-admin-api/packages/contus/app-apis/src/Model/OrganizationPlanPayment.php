<?php

namespace Contus\AppApi\Model;

use Contus\Base\Model;


class OrganizationPlanPayment extends Model
{
    protected $table = 'organization_plan_payment';

    protected $fillable = [
        'orgnization_id',
        'user_id',
        'plan_id',
        'start_date',
        'end_date',
        'transaction_id',
        'payment_gateway',
        'currency',
        'method',
        'payload',
        'amount',
        'status',
    ];
}