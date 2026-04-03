<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPrimaryAndDeviceNameToSubscriberAssignedDeviceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subscriber_assigned_device', function (Blueprint $table) {
            if (!Schema::hasColumn('subscriber_assigned_device', 'device_name')) {
                $table->string('device_name')->nullable()->after('price');
            }
            if (!Schema::hasColumn('subscriber_assigned_device', 'is_primary')) {
                $table->integer('is_primary')->default(0)->comment('0 = No, 1 = Yes')->after('device_name');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subscriber_assigned_device', function (Blueprint $table) {
            $table->dropColumn(['device_name', 'is_primary']);
        });
    }
}
