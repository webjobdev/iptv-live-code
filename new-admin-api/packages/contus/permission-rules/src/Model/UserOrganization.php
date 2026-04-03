<?php

namespace Contus\PermissionRule\Model;

use Carbon\Carbon;
use Contus\Organizations\Model\Organization;
use Contus\User\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserOrganization extends Model {

    use HasFactory;

    protected $table = 'user_organizations';

    protected $fillable = ['user_id', 'organization_id'];

    public function organization() {
        return $this->belongsTo(Organization::class, 'organization_id', 'id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
