<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMaxFailedLoginsToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name')->nullable()->after('name');
            $table->string('last_name')->nullable()->after('first_name');
            $table->unsignedBigInteger('permission_rule_id')->nullable()->after('user_group_id');
            $table->string('company')->nullable()->after('updator_id');
            $table->string('location')->nullable()->after('company');
            $table->boolean('is_super_admin')->nullable()->after('location')->comment('1 => Super Admin, 0 => Not Super Admin')->default(0);
            $table->boolean('can_change_password_for_next_login')->nullable()->after('is_super_admin')->comment('1 => yes, 0 => no')->default(0);
            $table->timestamp('is_log_in_at')->nullable()->after('can_change_password_for_next_login');
            $table->timestamp('is_log_out_at')->nullable()->after('is_log_in_at');
            $table->bigInteger('max_failed_logins')->nullable();
            $table->string('fcm_token')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
