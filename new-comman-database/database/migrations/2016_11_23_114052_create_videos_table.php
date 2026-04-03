<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('videos', function (Blueprint $table) {
            // $table->bigIncrements('id');
            // $table->string('title');
            // $table->text('subtitle');
            // $table->text('subtitle_path');
            // $table->string('slug');
            // $table->text('video_url');
            // $table->text('description');
            // $table->text('short_description');
            // $table->text('preview_image');
            // $table->text('thumbnail_image');
            // $table->text('thumbnail_path');
            // $table->char('video_duration')->default('0:00');
            // $table->boolean('is_hls');
            // $table->text('hls_playlist_url');
            // $table->text('aws_prefix');
            // $table->text('selected_thumb');
            // $table->boolean('youtube_live');
            // $table->string('youtube_id');
            // $table->string('scheduledStartTime');
            // $table->string('nextPageToken');
            // $table->string('totalResults');
            // $table->text('disclaimer');
            // $table->integer('is_feature_time');
            // $table->bigInteger('country_id');
            // $table->string('fine_uploader_uuid');
            // $table->string('fine_uploader_name');
            // $table->text('subscription');
            // $table->string('pipeline_id');
            // $table->string('job_id');
            // $table->string('job_status');
            // $table->tinyInteger('is_featured')->default(0);
            // $table->tinyInteger('is_subscription')->default(0);
            // $table->tinyInteger('trailer_status')->default(0);
            // $table->date('published_on')->nullable();
            // $table->integer('video_order')->default(0);
            // $table->string('liveStatus');
            // $table->string('youtubePrivacy');
            // $table->string('presenter');
            // $table->string('pdf');
            // $table->string('word');
            // $table->string('mp3');
            // $table->string('broadcast_location')->nullable();
            // $table->string('stream_id')->nullable();
            // $table->string('source_url')->nullable();
            // $table->string('encoder_type')->nullable();
            // $table->string('hosted_page_url')->nullable();
            // $table->string('username')->nullable();
            // $table->string('password')->nullable();
            // $table->string('stream_name')->nullable();
            // $table->tinyInteger('notification_status')->default(0);
            // $table->tinyInteger('is_active')->default(0);
            // $table->bigInteger('creator_id')->default(0);
            // $table->bigInteger('updator_id')->default(0);
            // $table->tinyInteger('is_archived')->default(0);
            // $table->timestamp('archived_on');
            // $table->timestamps();



            $table->bigIncrements('id');
            $table->string('title')->nullable();
            $table->string('title_two')->nullable();
            $table->string('subtitle')->nullable();
            $table->string('slug')->unique()->nullable();

            $table->text('video_url')->nullable();
            $table->longText('description')->nullable();
            $table->string('thumbnail_image')->nullable();
            $table->string('poster_image')->nullable();
            $table->string('mobile_poster_image')->nullable();

            $table->string('video_duration')->nullable();
            $table->integer('video_height')->nullable();
            $table->string('video_quality')->nullable();
            $table->boolean('is_hls')->default(0);

            $table->tinyInteger('recording_status')->default(0)->comment('0-Unprocessed, 1-Processing, 2-Completed');

            $table->string('aspect_ratio')->nullable();
            $table->text('hls_playlist_url')->nullable();

            $table->string('sprite_image')->nullable();
            $table->tinyInteger('sprite_image_status')->default(0);

            $table->string('aws_prefix')->nullable();
            $table->boolean('is_live')->default(0);

            $table->tinyInteger('live_recorded_status')->default(0)->comment('0-Unprocessed, 1-Processing, 2-Completed');
            $table->tinyInteger('live_recording_confirmation')->default(0)->comment('0-Unprocessed, 1-Processing, 2-Completed');

            $table->dateTime('scheduledStartTime')->nullable();
            $table->dateTime('scheduledEndTime')->nullable();

            $table->string('fine_uploader_uuid')->nullable();
            $table->string('fine_uploader_name')->nullable();

            $table->string('subscription')->nullable();
            $table->string('pipeline_id')->nullable();
            $table->string('job_id')->nullable();
            $table->string('job_status')->nullable();
            $table->string('transcode_status')->nullable();

            $table->integer('upload_percentage')->default(0);
            $table->boolean('is_subscription')->default(0);

            $table->dateTime('published_on')->nullable();
            $table->integer('video_order')->default(0);

            $table->string('liveStatus')->nullable();
            $table->string('presenter')->nullable();
            $table->string('broadcast_location')->nullable();

            $table->string('stream_id')->nullable();
            $table->text('source_url')->nullable();
            $table->string('encoder_type')->nullable();

            $table->string('username')->nullable();
            $table->string('password')->nullable();
            $table->string('stream_name')->nullable();

            $table->boolean('notification_status')->default(0);
            $table->boolean('is_active')->default(0);
            $table->boolean('is_premium')->default(0);

            $table->integer('release_year')->nullable();
            $table->boolean('is_parental')->default(0);

            $table->boolean('is_notify')->default(0);
            $table->boolean('is_notified')->default(0);

            $table->string('trailer_url')->nullable();
            $table->tinyInteger('trailer_status')->default(0);
            $table->string('trailer_hls_url')->nullable();
            $table->string('trailer_hls_prefix')->nullable();
            $table->string('trailer_jobid')->nullable();

            $table->unsignedBigInteger('creator_id')->nullable();
            $table->unsignedBigInteger('updator_id')->nullable();

            $table->boolean('is_archived')->default(0);
            $table->dateTime('archived_on')->nullable();

            $table->bigInteger('view_count')->default(0);
            $table->string('audio_language')->nullable();
            $table->string('video_size')->nullable();
            $table->string('active_presets')->nullable();

            $table->decimal('price', 10, 2)->nullable();

            $table->boolean('is_webseries')->default(0);
            $table->integer('episode_order')->nullable();

            $table->integer('age_limit')->nullable();
            $table->boolean('is_kids')->default(0);
            $table->boolean('hide_web')->default(0);

            $table->boolean('files_deleted')->default(0);

            // $table->text('organization')->nullable();
            $table->foreignId('organization')->nullable()->constrained('organization_details')->onDelete('cascade');

            $table->string('drm_profile')->nullable();
            $table->string('playback_token')->nullable();
            $table->text('policy')->nullable();
            $table->string('content_sets')->nullable();
            $table->string('drm_type')->nullable();

            $table->string('streaming_provider')->nullable();
            $table->string('live_streaming_provider')->nullable();

            $table->dateTime('recordingStartTime')->nullable();
            $table->dateTime('recordingEndTime')->nullable();

            $table->dateTime('available_until')->nullable();
            $table->integer('days')->nullable();

            $table->date('publish_date')->nullable();
            $table->boolean('scheduled_publishing')->default(0)->comment('0-off, 1-on');

            $table->tinyInteger('age_rating')->default(0)->comment('0-Default, 1-Country based');

            $table->tinyInteger('catch_up_status')->default(0)->comment('0-inactive, 1-active');

            $table->tinyInteger('live_rewind_status')->default(0)->comment('0-inactive, 1-active');

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
        Schema::drop('videos');
    }
}
