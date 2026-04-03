<?php

namespace Contus\PermissionRule\Model;

use Contus\Organizations\Model\Organization;
use Contus\PermissionRule\Model\Rule;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermissionRulesOrg extends Model
{
    use HasFactory;

    protected $table = 'permission_rules_organizations';

    protected $fillable = [
        'permission_rule_id',
        'organization_id',
        'created_by',
    ];

    public $timestamps = true;

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'org_id', 'id');
    }

    public function rule()
    {
        return $this->belongsTo(Rule::class, 'rule_id', 'id');
    }
}
