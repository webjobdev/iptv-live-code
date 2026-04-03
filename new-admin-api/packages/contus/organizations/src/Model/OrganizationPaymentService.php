<?php

namespace Contus\Organizations\Model;

use Contus\Subscribers\Model\OrgSubscribers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrganizationPaymentService extends Model
{
    use HasFactory;

    protected $table = 'organization_payment_services';

    protected $fillable = [
        'organization_id',
        'payment_service_id',
        'default',
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
            'organization_id', // Local key on OrganizationPaymentService table
            'id'              // Local key on OrganizationDetail table
        );
    }
}
