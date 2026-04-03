<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrganizationTypeAndOtherColumnToVideos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('videos', function (Blueprint $table) {
            $table->foreignId('organization')->nullable()->constrained('organization_details')->onDelete('cascade');
            $table->unsignedBigInteger('drm_profile')->nullable();
            $table->foreign('drm_profile')->nullable()->references('drm_details_id')->on('drm_profile_details')->onDelete('cascade');
            $table->string('playback_token')->nullable();
            $table->string('policy')->nullable();
            $table->json('content_sets')->nullable();
            $table->string('drm_type')->nullable();
            $table->string('streaming_provider')->nullable();
            $table->string('live_streaming_provider')->nullable();
            $table->string('recordingStartTime')->nullable();
            $table->string('recordingEndTime')->nullable();
            $table->string('publish_date')->nullable();
            $table->boolean('scheduled_publishing')->nullable()->comment("0 = off, 1 = on");
            $table->boolean('age_rating')->nullable()->comment("0 = Default Age Rating, 1 = Country based Age Rating");
            $table->boolean('catch_up_status')->nullable()->comment("0 = in_active, 1 = active");
            $table->boolean('live_rewind_status')->nullable()->comment("0 = in_active, 1 = active");
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
            //
        });
    }
}
