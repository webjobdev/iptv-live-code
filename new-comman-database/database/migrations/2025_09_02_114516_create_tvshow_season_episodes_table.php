<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTvshowSeasonEpisodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tvshow_season_episodes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tv_show_id')->nullable()->constrained('tv_shows')->onDelete('cascade');
            $table->foreignId('season_id')->nullable()->constrained('tv_shows')->onDelete('cascade');
            // $table->string('background_image')->nullable();
            $table->string('poster_image')->nullable();
            $table->string('thumbnail_image')->nullable();
            $table->string('episode_name')->nullable();
            $table->string('episode_number')->nullable();
            $table->string('streaming_url')->nullable();
            $table->string('description')->nullable();
            $table->string('directors')->nullable();
            $table->string('presenter')->nullable();
            $table->string('resolution')->nullable();
            $table->string('length')->nullable();
            $table->text('content_sets')->nullable();
            $table->string('release_date')->nullable();
            $table->string('scheduled_time')->nullable();
            $table->string('expire_scheduled_time')->nullable();
            $table->string('publish_date')->nullable();
            $table->string('drm_type')->nullable();
            $table->unsignedBigInteger('drm_profile')->nullable();
            $table->foreign('drm_profile')->nullable()->references('drm_details_id')->on('drm_profile_details')->onDelete('cascade');
            $table->text('policy')->nullable();
            $table->string('playback_token')->nullable();
            $table->text('content_setes')->nullable();
            $table->boolean('scheduled_publishing')->default(0)->comment("0 For Not scheduled_publishing, 1 For scheduled_publishing");
            $table->boolean('publish_now')->default(0)->comment("0 For Not Publish, 1 For Publish");
            $table->boolean('is_active')->default(0)->comment("0 for inActive, 1 for Active");
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
        Schema::dropIfExists('tvshow_season_episodes');
    }
}
