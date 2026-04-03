<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShoppingCartCustomPlansTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('shopping_cart_custom_plans', function (Blueprint $table) {
            $table->id();
            $table->string('plan_name');
            $table->string('description')->nullable();
            $table->string('cover_image')->nullable();
            $table->string('label')->comment('1 => true, 0 => false')->default(0);
            $table->string('additional_info')->nullable();
            $table->string('status')->comment('1 => enable, 0 => disable')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('shopping_cart_custom_plans');
    }
}
