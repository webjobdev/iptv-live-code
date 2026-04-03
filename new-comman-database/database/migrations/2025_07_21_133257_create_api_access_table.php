<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApiAccessTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('api_access', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('login');
            $table->string('token');
            $table->unsignedBigInteger('organization_id');
            $table->unsignedBigInteger('subscription_id');
            $table->string('add_on');
            $table->string('status')->comment("active = 1, inactive = 0");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('api_access');
    }
}
