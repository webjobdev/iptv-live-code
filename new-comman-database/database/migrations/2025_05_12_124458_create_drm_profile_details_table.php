<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDrmProfileDetailsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('drm_profile_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('drm_details_id')->constrained('drm_details')->onDelete('cascade');
            $table->string('drm_type')->nullable();
            $table->string('robustness')->nullable();
            $table->string('drm_provider')->nullable();
            $table->string('fps_certificate')->nullable();
            $table->string('integration_type')->nullable();
            $table->string('authorization_url')->nullable();
            $table->string('hardware_drm_required')->nullable();
            $table->string('rooted_devices_allowed')->nullable();
            $table->string('output_protection_level')->nullable();
            $table->string('playready_security_level')->nullable();
            $table->string('license_persistent')->nullable()->comment('0 = no, 1 = yes');
            $table->string('license_duration')->nullable()->comment('duration in second');
            $table->string('is_active')->nullable()->comment('0 = in_active, 1 = aactive');
            $table->string('license_limitation')->nullable()->comment('0 = unlimited, 1 = limited');
            $table->string('hdcp_type')->nullable()->comment('type 0 = (All HDCP capable devices), type 1 = (HDCP 2.2+ only)');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('drm_profile_details');
    }
}
