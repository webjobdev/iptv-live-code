<?php

namespace Contus\Organizations\Model;

use Contus\Base\Model;

class BannerCarouselsSubscription extends Model
{
    protected $table = 'org_banner_carousels_subscription';

    protected $fillable = [
        'organization_id',
        'plan_id',
        'banner_id',
        'poster_image',
        'thumbnail_image',
        'resource_type',
        'select_platform',
        'name',
        'content_type',
        'target_link',
        'is_active',
    ];

    protected $casts = [
        'select_platform' => 'array'
    ];

    public function plan()
    {
        return $this->belongsTo(OrgMonetizationPlanss::class, 'plan_id', 'id');
    }
}