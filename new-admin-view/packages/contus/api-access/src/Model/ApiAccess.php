<?php

namespace Contus\ApiAccess\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiAccess extends Model {
    use HasFactory;

    protected $table = 'api_access';

    protected $fillable = ['login', 'token', 'name', 'organization_id'];
}
