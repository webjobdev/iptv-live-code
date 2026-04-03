<?php

namespace Contus\ApiAccess\Model;

use Contus\Base\Model;
use Contus\Organizations\Model\Organization;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ApiAccess extends Model {

    use HasFactory;

    protected $table = 'api_access';
    protected $fillable = ['name', 'login', 'token', 'organization_id', 'status'];

    public function organization() {
        return $this->belongsTo(Organization::class, 'organization_id', 'id');
    }
}
