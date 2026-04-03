<?php

namespace Contus\PermissionRule\Model;

use Carbon\Carbon;
use Contus\Organizations\Model\Organization;
use Contus\User\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rule extends Model {

    use HasFactory;

    protected $table = 'rules';

    protected $fillable = ['rule_name', 'organization_id'];

    public function organization() {
        return $this->belongsTo(Organization::class, 'organization_id', 'id');
    }

    public function permissions() {
        return $this->hasMany(PermissionRule::class, 'rule_id', 'id');
    }
}
