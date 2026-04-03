<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlayBackTokenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('play_back_token', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('type')->nullable();
            $table->string('secret_key')->nullable();
            $table->string('token_time')->nullable();
            $table->string('secret_generation_number')->nullable();
            $table->string('ignore_device_ip_verification')->nullable();
            $table->string('rsa_Private_key')->nullable();
            $table->string('url_format')->nullable();
            $table->boolean('is_active')->default(0)->nullable()->comment("0 for in active, 1 for active");
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
        Schema::dropIfExists('play_back_token');
    }
}
