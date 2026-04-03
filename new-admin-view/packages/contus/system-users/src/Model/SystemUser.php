<?php

namespace Contus\SystemUser\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemUser extends Model {

    use HasFactory;

    protected $table = 'system_users';
    protected $fillable = ['first_name', 'last_name', 'password', 'permission_rule_id', 'email', 'phone_number', 'company', 'location', 'max_failed_logins', 'status', 'is_super_admin', 'can_change_password_for_next_login'];
}
