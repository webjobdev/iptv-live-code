<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCurrencyConverterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('currency_converter', function (Blueprint $table) {
            $table->id();
            $table->string('token')->nullable();
            $table->string('refresh_rate_mode')->nullable();
            $table->string('refresh_rate')->nullable();
            $table->string('refresh_rate_unit')->nullable();
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
        Schema::dropIfExists('currency_converter');
    }
}
