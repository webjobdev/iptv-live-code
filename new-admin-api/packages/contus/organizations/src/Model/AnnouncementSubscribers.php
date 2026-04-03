<?php

namespace Contus\Organizations\Model;

use Carbon\Carbon;
use Contus\Customer\Models\Subscribers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnnouncementSubscribers extends Model {
    use HasFactory;

    protected $table = 'org_announcement_subscribers';

    protected $fillable = [
        'organization_id',
        'announcement_id',
        'subscriber_id',
    ];

    public function organization() {
        return $this->belongsTo(OrganizationDetail::class, 'organization_id', 'id');
    }

    public function announcements() {
        return $this->belongsTo(OrganizationAnnouncement::class, 'announcement_id', 'id');
    }

    public function subscriber() {
        return $this->belongsTo(OrgSubscribers::class, 'subscriber_id', 'id');
    }
}
