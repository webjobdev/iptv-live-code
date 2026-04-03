<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSystemUsersTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('system_users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('password');
            $table->unsignedBigInteger('permission_rule_id');
            $table->string('email');
            $table->string('phone_number');
            $table->string('company');
            $table->string('location');
            $table->string('max_failed_logins');
            $table->string('status')->comment('1 => enable, 0 => disable')->default(0);
            $table->string('is_super_admin')->comment('1 => true, 0 => false')->default(0);
            $table->string('can_change_password_for_next_login')->comment('1 => yes, 0 => no')->default(0);
            $table->timestamp('is_log_in_at');
            $table->timestamp('is_log_out_at');
            $table->string('ip_address');
            $table->string('fcm_token');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('system_users');
    }
}
