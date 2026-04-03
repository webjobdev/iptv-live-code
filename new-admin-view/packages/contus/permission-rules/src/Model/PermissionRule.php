<?php

namespace Contus\PermissionRule\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermissionRule extends Model {

    use HasFactory;

    protected $table = 'permission_rules';

    protected $fillable = ['rule_name', 'organization_id', 'module_name', 'view', 'create', 'edit', 'delete', 'hide', 'cash_payment', 'refund_payment', 'length_adjustment', 'security_search'];
}
