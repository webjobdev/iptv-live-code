<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGeoRestrictionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('geo_restrictions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type');
            $table->string('geo_ip_status')->default('0')->comment('0 => disable, 1 => enable');
            $table->string('geo_protection_status')->default('0')->comment('0 => disable, 1 => enable');
            $table->string('mode');
            $table->json('countries');
            $table->string('override_geo_restrictions');
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
        Schema::dropIfExists('geo_restrictions');
    }
}
