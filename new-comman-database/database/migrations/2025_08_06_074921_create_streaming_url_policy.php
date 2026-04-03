<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStreamingUrlPolicy extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('streaming_url_policy', function (Blueprint $table) {
            $table->id();
            $table->string('policy_name');
            $table->string('rules');
            $table->unsignedBigInteger('updated_by');
            $table->string('status')->comment("1 = true, 0 = false");

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('streaming_url_policy');
    }
}
