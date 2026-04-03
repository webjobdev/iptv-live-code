<?php

namespace Contus\Organization\Model;

use Contus\Base\Model as BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganizationPayment extends BaseModel {

    use HasFactory;

    protected $fillable = [
        'user_id',
        'plan_id',
        'organization_id',
        'payment_id',
        'currency',
        'method',
        'payload',
        'amount',
        'status',
    ];
}
