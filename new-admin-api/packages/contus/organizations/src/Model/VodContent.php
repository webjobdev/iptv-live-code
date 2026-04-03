<?php

namespace Contus\Organizations\Model;

use Contus\Base\Model;
use Contus\User\Models\User;
use Contus\Vod\Model\VideoOnDemad;

class VodContent extends Model {
    protected $table = 'vod_content_set';

    protected $fillable = [
        'organization_id',
        'name',
        'assigned_vod',
        'currency',

        // Buy monetization
        'monitization_type_buy',
        'payment_method_buy',
        'buy_price',

        // Rent monetization
        'monitization_type_rent',
        'payment_method_rent',
        'rent_price',

        'period',
        'period_type',
        'cover_image',
        'description',
        'by_user',
        'is_active',
    ];

    public function getorg() {
        $org = $this->belongsTo(OrganizationDetail::class, 'organization_id', 'id');
        return $org;
    }

    public function getuser() {
        $user = $this->belongsTo(User::class, 'by_user', 'id');
        return $user;
    }

    protected $casts = [
        'assigned_vod' => 'array',
    ];

    protected $appends = ['vods'];

    function getVodsAttribute() {
        $vods = json_decode($this->assigned_vod, true);
        // return $vods;
        $vodIds = collect($vods)->pluck('id');
        return VideoOnDemad::whereIn('id', $vodIds)->get();
    }

    public function vods() {
        return $this->belongsTo(VodContent::class, 'organization_id', 'id');
    }
}
