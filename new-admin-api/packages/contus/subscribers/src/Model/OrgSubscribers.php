<?php

namespace Contus\Subscribers\Model;

use Contus\Organizations\Model\OrganizationDetail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class OrgSubscribers extends Model
{
    use HasFactory;

    protected $table = 'org_subscribers';

    protected $fillable = [
        'organization_id',
        'organization_name',
        'subscription_and_payments_id',
        'account_number',
        'pin_code',
        'user_name',
        'password',
        'first_name',
        'last_name',
        'email',
        'phone_number_code',
        'phone_number',
        'address',
        'city',
        'zip_code',
        'country',
        'state',
        'language',
        'date_of_birth',
        'timezone',
    ];

    public function subscription_and_payments_details()
    {
        // return $this->belongsTo(OrgSubscriberAndPayment::class, 'subscription_and_payments_id');
        return $this->hasMany(OrgSubscriberAndPayment::class, 'subscriber_id');
    }

    public function fetch_org_subscribers_data()
    {
        $leftJoin = DB::table('org_subscription_and_payments')
            ->leftJoin('org_subscribers', 'org_subscribers.id', '=', 'org_subscription_and_payments.subscriber_id')
            ->select('org_subscription_and_payments.*', 'org_subscribers.*')
            ->get();

        $rightJoin = DB::table('org_subscribers')
            ->leftJoin('org_subscription_and_payments', 'org_subscription_and_payments.subscriber_id', '=', 'org_subscribers.id')
            ->select('org_subscription_and_payments.*', 'org_subscribers.*')
            ->whereNull('org_subscription_and_payments.subscriber_id')
            ->get();

        return $leftJoin->merge($rightJoin);
    }

    public function FetchOrganization()
    {
        return $this->belongsTo(OrganizationDetail::class, 'organization_id', 'id');
    }

    // public function device_detaile() {
    //     return $this->belongsTo(OrgDevices::class, 'device_id');
    // }

    // public function subscriber_detaile() {
    //     return $this->belongsTo(OrgSubscribers::class, 'subscription_and_payments_id');
    // }

    public function devices()
    {
        return $this->hasMany(OrgDevices::class, 'subscriber_id', 'id');
    }

    public function channels()
    {
        return $this->hasMany(SubCustomStream::class, 'subscriber_id', 'id');
    }
}
