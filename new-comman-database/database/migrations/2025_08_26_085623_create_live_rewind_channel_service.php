<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLiveRewindChannelService extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('live_rewind_channel_service', function (Blueprint $table) {
            $table->id();
            $table->foreignId('channel_id')->nullable()->constrained('channels')->onDelete('cascade');
            $table->string('drm_type')->nullable();
            $table->unsignedBigInteger('drm_profile')->nullable();
            $table->foreign('drm_profile')->nullable()->references('drm_details_id')->on('drm_profile_details')->onDelete('cascade');
            $table->string('streaming_provider')->nullable();
            $table->boolean('custome_streaming_url')->default('0')->comment("0 For not allowed, 1 For allowed");
            $table->string('url')->nullable();
            $table->string('playback_token')->nullable();
            $table->string('token_generator')->nullable();
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
        Schema::dropIfExists('live_rewind_channel_service');
    }
}
