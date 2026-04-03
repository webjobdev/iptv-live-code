<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriberDeviceOrganizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriber_device_organizations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subscriber_device_id')->nullable();
            $table->unsignedBigInteger('organization_id')->nullable();
            $table->bigInteger('create_by')->nullable();
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
        Schema::dropIfExists('subscriber_device_organizations');
    }
}
