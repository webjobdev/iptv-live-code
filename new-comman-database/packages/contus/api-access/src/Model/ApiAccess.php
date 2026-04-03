<?php

namespace Contus\ApiAccess\Model;

use Contus\Base\Model as BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiAccess extends BaseModel {

    use HasFactory;

    protected $table = ['api_access'];
    protected $fillable = ['name', 'login', 'token', 'organization_id', 'status'];
}
