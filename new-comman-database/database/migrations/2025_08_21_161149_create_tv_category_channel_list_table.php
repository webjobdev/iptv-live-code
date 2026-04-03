<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTvCategoryChannelListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tv_category_channel_list', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('categorie_id')->nullable();
            $table->foreignId('channel_id')->nullable()->constrained('channels')->onDelete('cascade');
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
        Schema::dropIfExists('tv_category_channel_list');
    }
}
