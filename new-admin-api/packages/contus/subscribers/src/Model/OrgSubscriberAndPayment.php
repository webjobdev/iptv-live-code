<?php

namespace Contus\Subscribers\Model;

use Contus\Base\Model;
use Contus\Organizations\Model\OrgMonetizationPlanss;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrgSubscriberAndPayment extends Model
{
    use HasFactory;

    protected $table = 'org_subscription_and_payments';

    protected $fillable = [
        'subscriber_id',
        'subscriber_payment_id',
        'product_type',
        'activation',
        'subscription',
        'length_type',
        'length',
        'day_month_type',
        'start_date',
        'end_date',
        'adjust_length',
        'payment_service',
        'payment_status',
        'subscribable_type',
        'cash_location',
        'payment_currency',
        'total',
        'accessory',
        'custom_charge_comment',
        'bundels',
        'device',
        'prorate_subsciption',
        'terms_of_agreement',
        'is_active',
        'auto_pay'
    ];

    protected $casts = [
        'device' => 'array',
    ];

    public function subscriber_detail()
    {
        return $this->belongsTo(OrgSubscribers::class, 'subscriber_id', 'id');
    }

    public function transaction_detail()
    {
        return $this->belongsTo(OrgSubscriberPayment::class, 'subscriber_payment_id', 'id');
    }

    public function PlanDetail()
    {
        return $this->belongsTo(OrgMonetizationPlanss::class, 'subscription', 'id');
    }
}
