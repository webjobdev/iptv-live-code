<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrgSubscriberDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('org_subscriber_devices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscriber_id')->constrained('org_subscribers')->onDelete('cascade');
            $table->string('brand_model')->nullable();
            $table->string('mac_address')->nullable();
            $table->string('serial_number')->nullable();
            $table->string('identifier')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('location')->nullable();
            // $table->string('country')->nullable();
            // $table->string('latitude')->nullable();
            // $table->string('longitude')->nullable();
            $table->time('last_session')->nullable();
            $table->string('status')->nullable()->comment("0 = in_active, 1 = active");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('org_subscriber_devices');
    }
}
