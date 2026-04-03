<?php

namespace Contus\Organizations\Model;

use Contus\Organizations\Model\OrganizationDetail;
use Contus\Subscribers\Model\OrgSubscriberAndPayment;
use Contus\Subscribers\Model\SubCustomStream;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Contus\Base\Contracts\AttachableModel as AttachableModel;

class OrgSubscribers extends Model implements JWTSubject, AuthenticatableContract, AuthorizableContract, CanResetPasswordContract, AttachableModel
{
    use HasFactory, Authenticatable, Authorizable, CanResetPassword;

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
        'ip_address'
    ];

    // protected $appends = ['avatar_url'];

    // public function getAvatarUrlAttribute()
    // {
    //     if ($this->avatar) {
    //         return asset($this->avatar);
    //     }
    //     return null; // or return a default avatar URL
    // }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function getFileModel()
    {
        return $this;
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

    public function organization()
    {
        return $this->belongsTo(OrganizationDetail::class, 'organization_id', 'id');
    }

    public function subscription_payment_detail()
    {
        $data = $this->hasMany(OrgSubscriberAndPayment::class, 'subscriber_id');
        return $data;
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

    // this relationship for dashboard

    public function devices()
    {
        return $this->hasMany(OrgDevices::class, 'subscriber_id', 'id');
    }

    public function channels()
    {
        return $this->hasMany(SubCustomStream::class, 'subscriber_id', 'id');
    }
}
