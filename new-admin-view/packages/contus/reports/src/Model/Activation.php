<?php

namespace Contus\Reports\Model;

use Contus\Base\Model;
use Contus\Organizations\Model\OrganizationDetail;
use Contus\Settings\Model\SubscriberSetting;
use Contus\Subscribers\Model\OrgSubscribers;
use Contus\User\Models\User;

class Activation extends Model
{
    protected $table = 'activation_reports';

    protected $fillable = [
        'report_name',
        'report_period',
        'organization',
        'users',
        'subscription_plan',
        'subscription_plan_type',
        'subscription_length_from_date',
        'subscription_length_to_date',
        'payment_service',
        'autopay',
        'available_plan',
        'generate',
        'created_by'
    ];

    public function GetOrg()
    {
        $org = $this->belongsTo(OrganizationDetail::class, 'organization', 'id');
        return $org;
    }

    public function UserList()
    {
        $user = $this->belongsTo(OrgSubscribers::class, 'users', 'id');
        return $user;
    }
    
    public function GetUser()
    {
        $data = $this->belongsTo(User::class, 'created_by', 'id');
        return $data;
    }
}