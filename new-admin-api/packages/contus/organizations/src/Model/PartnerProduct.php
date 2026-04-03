<?php

namespace Contus\Organizations\Model;

use Contus\Base\Model;
use Contus\User\Models\User;

class PartnerProduct extends Model
{
    protected $table = 'organization_partner_product';
    protected $fillable = [
        'organization_id',
        'partner_program',
        'partner_name',
        'partner_image',
        'partner_description',
        'partner_id',
    ];

    public function ByUser(){
        $user = $this->belongsTo(User::class, 'by_user', 'id');
        return $user;
    }
}
