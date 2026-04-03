<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTvCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tv_category', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization')->nullable()->constrained('organization_details')->onDelete('cascade');
            $table->unsignedBigInteger('channel_id')->nullable();
            $table->string('sub_category_id')->nullable();
            $table->string('tv_categorie_name')->nullable();
            $table->string('category_name')->nullable();
            $table->string('categorie_id')->nullable();
            $table->string('category_order')->nullable();
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
        Schema::dropIfExists('tv_category');
    }
}
