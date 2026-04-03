<?php

namespace Contus\PermissionRule\Model;

use Carbon\Carbon;
use Contus\Organizations\Model\Organization;
use Contus\User\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RulePermissions extends Model {

    use HasFactory;

    protected $table = 'rule_permissions';

    protected $fillable = ['rule_id', 'permission_module_name', 'view', 'create', 'edit', 'delete', 'hide', 'cash_payment', 'refund_payment', 'length_adjustment', 'security_search'];

    protected $attributes = [
        'view' => 0,
        'create' => 0,
        'edit' => 0,
        'delete' => 0,
        'hide' => 0,
        'cash_payment' => 0,
        'refund_payment' => 0,
        'length_adjustment' => 0,
        'security_search' => 0,
    ];

    public function rules() {
        return $this->belongsTo(Rule::class, 'rule_id', 'id');
    }
    
    public function getFormattedUpdatedAtAttribute() {
        return $this->updated_at
            ? Carbon::parse($this->updated_at)->format('d M Y')
            : null;
    }

}
