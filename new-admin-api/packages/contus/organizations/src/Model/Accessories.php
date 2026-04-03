<?php

namespace Contus\Organizations\Model;

use Contus\Base\Model;
use Contus\User\Models\User;

class Accessories extends Model {
    protected $table = 'organization_accessories';

    protected $fillable = [
        'organization_id',
        'accessories',
        'accessories_type',
        'identifier',
        'identifier_auto',
        'description',
        'currency',
        'price',
        'by_user',
        'is_active',
    ];

    public function ByUser() {
        $user = $this->belongsTo(User::class, 'by_user', 'id');
        return $user;
    }

    // public function monetizationPlanss() {
    //     return $this->belongsTo(OrgMonetizationPlanss::class, 'org_monetzn_accessories_id', 'id');
    // }
}
