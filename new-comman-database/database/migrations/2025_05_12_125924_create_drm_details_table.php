<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDrmDetailsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('drm_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('drm_id')->constrained('drms')->onDelete('cascade');
            $table->string('drm_name')->nullable();
            $table->string('drm_provider')->nullable();
            $table->string('px_value')->nullable();
            $table->string('account_id')->nullable();
            $table->string('site_key')->nullable();
            $table->string('access_key')->nullable();
            $table->string('publish_now')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('drm_details');
    }
}
