<?php

namespace Contus\SystemUser\Model;

use Carbon\Carbon;
use Contus\Base\Model;
use Contus\PermissionRule\Model\Rule;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Request;

class SystemUser extends Model {

    use HasFactory;

    protected $table = 'system_users';

    protected $fillable = ['first_name', 'last_name', 'password', 'permission_rule_id', 'email', 'phone_number', 'company', 'location', 'max_failed_logins', 'status', 'is_super_admin', 'can_change_password_for_next_login', 'is_log_in_at', 'is_log_out_at', 'ip_address', 'fcm_token'];

    protected $appends = ['formatted_updated_at'];

    public function rules() {
        return $this->belongsTo(Rule::class, 'permission_rule_id', 'id');
    }

    public function getFormattedUpdatedAtAttribute() {
        return $this->updated_at
            ? Carbon::parse($this->updated_at)->format('d M Y H:i')
            : null;
    }

    public static function booted() {
        static::creating(function ($model) {
            $model->ip_address = Request::ip();
        });
    }
}
