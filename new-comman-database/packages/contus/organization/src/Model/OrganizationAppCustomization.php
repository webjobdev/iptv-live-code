<?php

namespace Contus\Organization\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganizationAppCustomization extends Model {
    use HasFactory;

    protected $fillable = [
        'add_banner',
        'privacy_policy',
        'feedback',
        'user_agreement',
        'reports'
    ];
}
