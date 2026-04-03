<?php

namespace Contus\Organizations\Model;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganizationAnnouncement extends Model {
    use HasFactory;

    protected $table = 'organization_announcements';

    protected $fillable = [
        'organization_id',
        'subject',
        'message',
        'announcement_subscribers_id',
        'created_by',
    ];

    protected $appends = ['formatted_created_at'];

    public function getFormattedcreatedAtAttribute() {
        return $this->created_at
            ? Carbon::parse($this->created_at)->format('d-m-Y H:i')
            : null;
    }

    public function organization() {
        return $this->belongsTo(OrganizationDetail::class, 'organization_id', 'id');
    }

    public function announcementSubscribers() {
        return $this->hasMany(AnnouncementSubscribers::class, 'id', 'announcement_subscribers_id');
    }

    public function user() {
        return $this->hasMany(User::class, 'id', 'created_by');
    }
}
