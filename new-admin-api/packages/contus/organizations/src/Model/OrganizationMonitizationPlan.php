<?php

namespace Contus\Organizations\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use function Ramsey\Uuid\v1;

class OrganizationMonitizationPlan extends Model {
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'amount',
        'description',
        'duration',
        'is_active',
        'organization_id'
        // 'type',
    ];

    public function contentSets() {
        return $this->hasMany(OrganizationContentSets::class, 'monitization_plans_id', 'id');
    }

    public function subscriptions() {   
        return $this->hasMany(OrganizationSubscription::class, 'subscribable_id');
    }
}
