<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganizationAppCustomizationTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('organization_app_customizations', function (Blueprint $table) {
            $table->id();
            $table->string('add_banner')->nullable();
            $table->string('privacy_policy')->nullable( );
            $table->string('feedback')->nullable();
            $table->string('user_agreement')->nullable();
            $table->string('reports')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('organization_app_customizations');
    }
}
