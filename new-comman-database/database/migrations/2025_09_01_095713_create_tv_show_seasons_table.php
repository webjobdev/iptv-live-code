<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTvShowSeasonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tv_show_seasons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tv_show_id')->nullable()->constrained('tv_shows')->onDelete('cascade');
            $table->string('poster_image')->nullable();
            $table->string('thumbnail_image')->nullable();
            $table->string('title')->nullable();
            $table->bigInteger('season_number')->nullable();
            $table->string('description')->nullable();
            $table->string('directors')->nullable();
            $table->string('presenter')->nullable();
            $table->text('content_sets')->nullable();
            $table->string('release_date')->nullable();
            $table->string('scheduled_time')->nullable();
            $table->string('expire_scheduled_time')->nullable();
            $table->string('publish_date')->nullable();
            $table->boolean('expire_time_unlimited')->default(0)->comment("0 For not UNlimited, 1 For Limited");
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
        Schema::dropIfExists('tv_show_seasons');
    }
}
