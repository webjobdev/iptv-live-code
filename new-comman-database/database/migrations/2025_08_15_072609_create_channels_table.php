<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChannelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('channels', function (Blueprint $table) {
            $table->id();
            // $table->text('organization')->nullable();
            $table->foreignId('organization')->nullable()->constrained('organization_details')->onDelete('cascade');
            $table->string('drm_type')->nullable();
            $table->unsignedBigInteger('drm_profile')->nullable();
            $table->foreign('drm_profile')->nullable()->references('drm_details_id')->on('drm_profile_details')->onDelete('cascade');
            $table->string('poster_images')->nullable();
            $table->string('channel_name')->nullable();
            $table->string('sorting_number')->nullable();
            $table->string('language')->nullable();
            $table->string('video_quality')->nullable();
            $table->string('streaming_url')->nullable();
            $table->string('policy')->nullable();
            $table->string('playback_token')->nullable();
            $table->string('epg_id')->nullable();
            $table->text('content_sets')->nullable();
            $table->text('geo_block_country_list')->nullable();
            $table->string('pin_locked')->nullable()->default(0)->comment('1 for pin lock, 0 for no pin lock');
            $table->string('geo_policy')->nullable()->default(0)->comment('1 for enabled, 0 for disabled');
            $table->string('group_chat')->nullable()->default(0)->comment('1 for enabled, 0 for disabled');
            $table->string('is_active')->nullable()->default(0)->comment('1 for active, 0 for inactive');
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
        Schema::dropIfExists('channels');
    }
}
