<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('videos', function (Blueprint $table) {
            // $table->text('poster_image')->nullable();
            // $table->string('mobile_poster_image')->nullable();
            // $table->string('view_count')->nullable();
            // $table->string('audio_language')->nullable();
            // $table->string('video_size')->nullable();
            // $table->string('active_presets')->nullable();
            // $table->string('price')->nullable();
            // $table->string('is_webseries')->nullable();
            // $table->string('episode_order')->nullable();
            // $table->string('age_limit')->nullable();
            // $table->string('is_kids')->nullable();
            // $table->string('hide_web')->nullable();
            // $table->string('files_deleted')->nullable();
            // $table->foreignId('organization')->nullable()->constrained('organization_details')->onDelete('cascade');
            // $table->unsignedBigInteger('drm_profile')->nullable();
            // $table->foreign('drm_profile')->nullable()->references('drm_details_id')->on('drm_profile_details')->onDelete('cascade');
            // $table->string('playback_token')->nullable();
            // $table->string('policy')->nullable();
            // $table->longText('content_sets')->nullable();
            // $table->string('drm_type')->nullable();
            // $table->string('streaming_provider')->nullable();
            // $table->string('live_streaming_provider')->nullable();
            // $table->string('recordingStartTime')->nullable();
            // $table->string('recordingEndTime')->nullable();
            // $table->string('available_until')->nullable();
            // $table->string('days')->nullable();
            // $table->string('publish_date')->nullable();
            // $table->boolean('scheduled_publishing')->nullable()->default(0)->comment("0 = off, 1 = on");
            // $table->boolean('age_rating')->nullable()->default(0)->comment("0 = Default Age Rating, 1 = Country based Age Rating");
            // $table->boolean('catch_up_status')->nullable()->default(0)->comment("0 = in_active, 1 = active");
            // $table->boolean('live_rewind_status')->nullable()->default(0)->comment("0 = in_active, 1 = active");


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('videos', function (Blueprint $table) {
            $table->dropColumn('poster_image');
        });
    }
}
