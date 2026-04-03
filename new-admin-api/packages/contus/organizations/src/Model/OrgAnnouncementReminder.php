<?php

namespace Contus\Organizations\Model;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrgAnnouncementReminder extends Model {
    use HasFactory;

    protected $table = 'org_announcement_reminders';

    protected $fillable = [
        'organization_id',
        'subject',
        'message',
        'day_before',
        'reminder_to',
        'created_by',
    ];

    protected $appends = ['formatted_created_at'];

    public function getFormattedcreatedAtAttribute() {
        return $this->created_at
            ? Carbon::parse($this->created_at)->format('d-m-Y H:i')
            : null;
    }

    public function user() {
        return $this->hasMany(User::class, 'id', 'created_by');
    }

    public function organization() {
        return $this->belongsTo(OrganizationDetail::class, 'organization_id', 'id');
    }
}
