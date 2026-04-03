<?php

namespace Contus\Organizations\Model;

use Contus\User\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganizationSubscription extends Model {
    use HasFactory;

    protected $fillable = [
        'user_id',
        'subscribable_type',
        'subscribable_id',
        'organization_id',
        'start_at',
        'end_at',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relationship: Subscription belongs to a Plan
    public function plan() {
        return $this->belongsTo(OrganizationMonitizationPlan::class, 'subscribable_id', 'id');
    }

    public function orgname() {
        return $this->belongsTo(OrganizationDetail::class, 'organization_id');
    }
    
}
