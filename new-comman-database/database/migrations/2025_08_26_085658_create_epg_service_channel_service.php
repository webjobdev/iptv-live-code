<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEpgServiceChannelService extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('epg_service_channel_service', function (Blueprint $table) {
            $table->id();
            $table->string('task_name')->nullable();
            $table->string('schedule_base')->nullable();
            $table->string('start_time')->nullable();
            $table->string('time_zone')->nullable();
            $table->string('shift_postfix')->nullable();
            $table->string('source_url')->nullable();
            $table->boolean('is_active')->default('0')->comment("0 For inActive, 1 For Active");
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
        Schema::dropIfExists('epg_service_channel_service');
    }
}
