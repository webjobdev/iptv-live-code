<?php

namespace Contus\Subscribers\Model;

use Carbon\Carbon;
use Contus\Base\Model;
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
        'bundels',
        'device',
        'prorate_subsciption',
        'terms_of_agreement',
        'is_active',
    ];

    public function subscriber_detail()
    {
        return $this->belongsTo(OrgSubscribers::class, 'subscriber_id');
    }

    public function transaction_detail()
    {
        return $this->belongsTo(OrgSubscriberPayment::class, 'subscriber_payment_id');
    }

    // In OrgSubscriberAndPayment
    public function TransactionDetail()
    {
        return $this->hasMany(OrgSubscriberPayment::class, 'payment_id', 'subscriber_payment_id');
    }


    protected $appends = ['is_active_status'];

    public function getIsActiveStatusAttribute()
    {
        return $this->end_date && Carbon::parse($this->end_date) >= Carbon::now() ? 'Active' : 'Inactive';
    }
}
