<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideoOnDemandTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('video_on_demand', function (Blueprint $table) {
            $table->id();
            // $table->text('organization')->nullable();
            $table->foreignId('organization')->nullable()->constrained('organization_details')->onDelete('cascade');
            $table->string('drm_type')->nullable();
            $table->unsignedBigInteger('drm_profile')->nullable();
            $table->foreign('drm_profile')->nullable()->references('drm_details_id')->on('drm_profile_details')->onDelete('cascade');
            $table->string('poster_image')->nullable();
            $table->string('thumbnail_image')->nullable();
            $table->string('title')->nullable();
            $table->string('description')->nullable();
            $table->integer('year')->nullable();
            $table->string('directors')->nullable();
            $table->string('presenter')->nullable();
            $table->string('timeParts')->nullable();
            $table->string('scheduled_time')->nullable();
            $table->string('expire_scheduled_time')->nullable();
            $table->string('publish_date')->nullable();
            $table->string('video_quality')->nullable();
            $table->string('streaming_url')->nullable();
            $table->string('trailer_url')->nullable();
            $table->string('playback_token')->nullable();
            $table->string('policy')->nullable();
            $table->string('content_sets')->nullable();
            $table->text('geo_block_country_list')->nullable();
            $table->string('category')->nullable();
            $table->string('age_rating')->nullable();
            $table->string('age_limit')->nullable();
            $table->string('is_parental')->nullable();
            $table->boolean('scheduled_publishing')->nullable()->default(0)->comment("0 for inActive, 1 for Active");
            $table->boolean('publish_now')->nullable()->default(0)->comment("0 for inActive, 1 for Active");
            $table->boolean('geo_policy')->nullable()->default(0)->comment("0 for inActive, 1 for Active");
            $table->boolean('is_active')->nullable()->default(0)->comment("0 for inActive, 1 for Active");

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
        Schema::dropIfExists('video_on_demand');
    }
}
