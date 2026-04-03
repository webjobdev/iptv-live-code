<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_services', function (Blueprint $table) {
            $table->id();
            $table->string('payment_provider')->nullable();
            $table->json('provider_data')->nullable();
            $table->boolean('is_active')->default(0)->nullable()->comment("0 for in active, 1 for active");
            $table->boolean('default')->default(0)->nullable()->comment("0 for not default, 1 for default");
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
        Schema::dropIfExists('payment_services');
    }
}
