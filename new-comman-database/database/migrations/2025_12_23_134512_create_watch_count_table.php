<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWatchCountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('watch_count', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("subscriber_id")->nullable();
            $table->string('type')->nullable();
            $table->unsignedBigInteger("series_id")->nullable();
            $table->unsignedBigInteger("movie_id")->nullable();
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
        Schema::dropIfExists('watch_count');
    }
}
