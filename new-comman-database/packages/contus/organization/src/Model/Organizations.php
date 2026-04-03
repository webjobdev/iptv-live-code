<?php

namespace Contus\Organization\Model;

use Contus\Base\Model as BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organizations extends BaseModel {
    use HasFactory;
    protected $fillable = ['organization_name'];
}
