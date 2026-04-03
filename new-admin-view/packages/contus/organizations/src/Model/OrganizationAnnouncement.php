<?php

namespace Contus\Organizations\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganizationAnnouncement extends Model
{
    use HasFactory;

    protected $fillable = [
        'announcement',
        'user_id'
    ];
}
