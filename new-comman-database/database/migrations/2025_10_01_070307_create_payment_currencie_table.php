<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentCurrencieTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_currencie', function (Blueprint $table) {
            $table->id();
            $table->string('currency_code')->nullable();
            $table->string('currency_symbol')->nullable();
            $table->string('position')->nullable();
            $table->string('sample')->nullable();
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
        Schema::dropIfExists('payment_currencie');
    }
}
