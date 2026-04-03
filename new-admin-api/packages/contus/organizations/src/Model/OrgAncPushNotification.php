<?php

namespace Contus\Organizations\Model;

use App\Models\User;
use Carbon\Carbon;
use Contus\Customer\Models\SubscriptionPlan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrgAncPushNotification extends Model {
    use HasFactory;

    protected $table = 'org_announcement_push_notifications';

    protected $fillable = [
        'organization_id',
        'name',
        'title',
        'description',
        'org_subscription_id',
        'subscriber_status',
        'platform',
        'resource_type',
        'publish',
        'created_by',
        'status',
    ];

    protected $casts = ['platform' => 'array'];

    protected $appends = ['formatted_created_at', 'formatted_updated_at'];

    public function getFormattedCreatedAtAttribute() {
        return $this->created_at
            ? Carbon::parse($this->created_at)->format('d-m-Y H:i')
            : null;
    }

    public function getFormattedUpdatedAtAttribute() {
        return $this->updated_at
            ? Carbon::parse($this->updated_at)->format('d-m-Y H:i')
            : null;
    }

     public function organization() {
        return $this->belongsTo(OrganizationDetail::class, 'organization_id', 'id');
    }

    public function user() {
        return $this->hasMany(User::class, 'id', 'created_by');
    }

    public function orgSubscription() {
        return $this->hasMany(OrganizationMonitizationPlan::class, 'id', 'org_subscription_id');
    }
}
