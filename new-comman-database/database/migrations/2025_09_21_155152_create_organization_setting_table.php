<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganizationSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organization_setting', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('organization_id');
            $table->string('time_zone')->nullable();
            $table->string('system_default')->nullable()->comment("0 For InActive, 1 For Active");
            $table->string('pin_code')->nullable();
            $table->string('random')->nullable()->comment("0 For InActive, 1 For Active");
            $table->string('screen_server')->nullable();
            $table->string('minutes')->nullable()->comment("0 For InActive, 1 For Active");
            $table->string('ss_system_default')->nullable()->comment("0 For InActive, 1 For Active");
            $table->string('stb_start_channel')->nullable()->comment("0 For InActive, 1 For Active");
            $table->string('channel_id')->nullable();
            $table->string('sorting_number')->nullable();
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
        Schema::dropIfExists('organization_setting_tabel');
    }
}
