<?php

namespace Contus\Organizations\Model;

use Contus\User\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use function Ramsey\Uuid\v1;

class OrgMonetizationPlanss extends Model
{
    use HasFactory;

    protected $table = 'org_monetization_planss';

    protected $fillable = [
        'organization_id',
        'subscription_name',
        'subscription_identifier',
        'platforms',
        'subscription_length',
        'subs_length_time_type',
        'subscription_type',
        'advertising',
        'currency',
        'price',
        'autopay',
        'created_by',
        'subscription_devices',
        'conditional_subscriptions',
        'conditional_content_addons',
        'conditional_accessories',
        'org_monetzn_content_set_id',
        'org_monetzn_accessories_id',
        'org_monetzn_partner_product_id',
        'org_monetzn_extra_partner_product_id',
        'created_by',
        'auto_scrolling',
        'second',
        'banners',
        'banner_carousel_is_active',
        'additional_device_price',
        'total_price',
        'use_org_settings',
        'subscription_price'
    ];

    protected $casts = [
        'platforms' => 'array',
        'org_monetzn_content_set_id' => 'array',
        'subscription_devices' => 'array',
        'conditional_subscriptions' => 'array',
        'conditional_content_addons' => 'array',
        'conditional_accessories' => 'array',
        'org_monetzn_accessories_id' => 'array',
        'org_monetzn_partner_product_id' => 'array',
        'org_monetzn_extra_partner_product_id' => 'array',
        'banners' => 'array',
        'additional_device_price' => 'array',
    ];

    protected $appends = ['accessories', 'partnerProduct', 'extraPartnerProduct'];

    public function organization()
    {
        return $this->belongsTo(OrganizationDetail::class, 'organization_id', 'id');
    }

    public function contentSets()
    {
        return $this->belongsTo(OrgMonetznSubsriptionContentSets::class, 'org_monetzn_content_set_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    function getAccessoriesAttribute()
    {
        return Accessories::with('ByUser')->where('id', $this->org_monetzn_accessories_id)->get();
    }

    function getPartnerProductAttribute()
    {
        return PartnerProduct::with('ByUser')->where('id', $this->org_monetzn_partner_product_id)->get();
    }

    function getExtraPartnerProductAttribute()
    {
        return PartnerProduct::with('ByUser')->where('id', $this->org_monetzn_extra_partner_product_id)->get();
    }

    public function ByUser()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function bannerSubscription()
    {
        return $this->hasMany(BannerCarouselsSubscription::class, 'plan_id', 'id');
    }
}
