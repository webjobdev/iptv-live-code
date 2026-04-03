<?php

namespace Contus\Subscribers\Model;

use Contus\Base\Model;
use Contus\Subscribers\Model\OrgSubscribers;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrgSubscriberPayment extends Model {
    use HasFactory;

    protected $table = 'org_subscriber_payment';

    protected $fillable = [
        'subscriber_id',
        'subscription_payments_id',
        'payment_id',
        'refund_id',
        'currency',
        'method',
        'payment_gateway',
        'payload',
        'refund_payload',
        'amount',
        'status',
        'is_active',
    ];

    public function subscriber_details() {
        return $this->belongsTo(OrgSubscribers::class, 'subscriber_id');
    }

    public function subscription_and_payments() {
        return $this->belongsTo(OrgSubscriberAndPayment::class, 'subscription_payments_id');
    }
}
