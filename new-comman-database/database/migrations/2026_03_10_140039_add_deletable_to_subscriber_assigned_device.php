<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeletableToSubscriberAssignedDevice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subscriber_assigned_device', function (Blueprint $table) {
            $table->boolean('deletable')->default(0)->after('is_primary')->comment('0 = No, 1 = Yes');
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
            //
        });
    }
}
