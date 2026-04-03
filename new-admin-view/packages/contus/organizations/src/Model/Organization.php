<?php

namespace Contus\Organizations\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;
    protected $table = 'organizations';

    protected $fillable = ['organization_name'];

    // public function details()
    // {
    //     return $this->hasOne(OrganizationDetail::class, 'organization_id');
    // }
}
