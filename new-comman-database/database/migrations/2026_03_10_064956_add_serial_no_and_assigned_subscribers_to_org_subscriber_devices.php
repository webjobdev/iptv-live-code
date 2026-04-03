<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSerialNoAndAssignedSubscribersToOrgSubscriberDevices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('org_subscriber_devices', function (Blueprint $table) {
            $table->string('device_redirect')->comment("0 = false, 1 = true");
            $table->string('timezone')->nullable();
            $table->string('security_code_required')->nullable();
            $table->string('security_code')->nullable();
            $table->string('firmware_version')->nullable();
            $table->string('isp')->nullable();
            $table->string('create_subscriber')->comment("0 = false, 1 = true")->nullable();
            $table->string('list')->nullable();
            $table->string('first_value')->nullable();
            $table->string('serial_mac_seperator')->nullable();
            $table->string('parse_file')->nullable();
            $table->json('assigned_subscribers')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('org_subscriber_devices', function (Blueprint $table) {
            //
        });
    }
}
