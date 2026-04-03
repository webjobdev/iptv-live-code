<?php

namespace Contus\Organizations\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;
    protected $table = 'organizations';

    protected $fillable = ['organization_name'];

    public function organization()
    {
        return $this->hasMany(OrganizationDetail::class);
    }

    public function monPlan()
    {
        return $this->hasMany(OrgMonetizationPlanss::class, 'organization_id', 'id');
    }

    // public function details()
    // {
    //     return $this->hasOne(OrganizationDetail::class, 'organization_id');
    // }
}
