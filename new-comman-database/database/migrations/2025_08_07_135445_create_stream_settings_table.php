<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStreamSettingsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('stream_settings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('node');
            $table->string('preset');
            $table->string('status_type');
            $table->string('input_role');
            $table->string('started');
            $table->string('last_reset');
            $table->string('restarts');
            $table->string('cpu');
            $table->string('rss');
            $table->unsignedBigInteger('created_by');
            $table->string('status')->comment("on => 1, off => 0");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('stream_settings');
    }
}
