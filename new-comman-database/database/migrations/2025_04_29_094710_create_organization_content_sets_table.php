<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganizationContentSetsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('organization_content_sets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('monitization_plans_id')->constrained('organization_monitization_plans')->onDelete('cascade');
            $table->string('video_and_sound_quality')->nullable();
            $table->string('resolution')->nullable();
            $table->string('supported_devices')->nullable();
            $table->string('download_devices')->nullable();
            $table->string('no_ad')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('organization_content_sets');
    }
}
