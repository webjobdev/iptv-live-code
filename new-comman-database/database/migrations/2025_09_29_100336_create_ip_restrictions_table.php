<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIpRestrictionsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('ip_restrictions', function (Blueprint $table) {
            $table->id();
            $table->string('mode');
            $table->json('ip_address');
            $table->string('geo_ip_status')->comment('0 => disable, 1 => enable');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('ip_restrictions');
    }
}
