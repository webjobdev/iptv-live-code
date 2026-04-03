<?php

namespace Contus\Organizations\Model;

use Contus\Settings\Model\PaymentService;
use Contus\Settings\Model\PaymentServiceCurrencyConverter;
use Contus\Subscribers\Model\OrgSubscribers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrganizationCurrencies extends Model
{
    use HasFactory;

    protected $table = 'organization_currencies';

    protected $fillable = [
        'organization_id',
        'currency_id',
        'is_active',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id', 'id');
    }

    public function organizationDetail()
    {
        return $this->hasOne(OrganizationDetail::class, 'organization_id', 'organization_id');
    }

    public function subscribers()
    {
        return $this->hasManyThrough(
            OrgSubscribers::class,
            OrganizationDetail::class,
            'organization_id', // Foreign key on OrganizationDetail table
            'organization_id', // Foreign key on OrgSubscribers table
            'organization_id', // Local key on OrganizationCurrencies table
            'id'              // Local key on OrganizationDetail table
        );
    }

    // public function currency()
    // {
    //     return $this->belongsTo(Currency::class, 'currency_id', 'id');
    // }
}
