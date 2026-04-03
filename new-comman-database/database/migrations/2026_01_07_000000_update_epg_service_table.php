<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateEpgServiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Update epg_service_channel_service
        Schema::table('epg_service_channel_service', function (Blueprint $table) {
            $table->timestamp('last_run')->nullable()->after('source_url');
            $table->timestamp('next_run')->nullable()->after('last_run');
        });

        // Create epg_service_executions table
        Schema::create('epg_service_executions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('epg_service_id');
            $table->string('status')->comment('OK, Corrupted, Failed');
            $table->integer('completed_programmes')->default(0);
            $table->text('fail_reason')->nullable();
            $table->timestamp('start_time')->nullable();
            $table->timestamp('finish_time')->nullable();
            $table->string('executed_by')->nullable();
            $table->timestamps();

            $table->foreign('epg_service_id')->references('id')->on('epg_service_channel_service')->onDelete('cascade');
        });

        // Create epg_programs table
        Schema::create('epg_programs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('channel_id')->nullable();
            $table->unsignedBigInteger('epg_service_id')->nullable();
            $table->string('epg_id')->nullable();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->dateTime('start_date_time')->nullable();
            $table->dateTime('end_date_time')->nullable();
            $table->string('category')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();

            $table->index(['channel_id', 'start_date_time', 'end_date_time']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('epg_programs');
        Schema::dropIfExists('epg_service_executions');
        Schema::table('epg_service_channel_service', function (Blueprint $table) {
            $table->dropColumn(['last_run', 'next_run']);
        });
    }
}
