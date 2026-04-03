<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDevicesTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->string('mac_address');
            $table->string('serial_no');
            $table->string('device_redirect')->comment("0 = false, 1 = true");
            $table->string('identifier')->nullable();
            $table->string('timezone')->nullable();
            $table->json('organization_id');
            $table->string('security_code_required')->nullable();
            $table->string('security_code')->nullable();
            $table->json('assigned_subscribers')->nullable();
            $table->string('device_model')->nullable();
            $table->string('firmware_version')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('isp')->nullable();
            $table->string('location')->nullable();
            $table->string('status')->comment("0 = false, 1 = true")->default('0');
            $table->string('create_subscriber')->comment("0 = false, 1 = true")->nullable();
            $table->string('list')->nullable();
            $table->string('first_value')->nullable();
            $table->string('serial_mac_seperator')->nullable();
            $table->string('parse_file')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('devices');
    }
}
