<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriberSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriber_setting', function (Blueprint $table) {
            $table->id();
            $table->string('product_type')->nullable();
            $table->string('days')->nullable();
            $table->string('accessories_name')->nullable();
            $table->string('device_type')->nullable();
            $table->string('month_type')->nullable();
            $table->float('price')->nullable();
            $table->string('is_active')->nullable();
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
        Schema::dropIfExists('subscriber_setting');
    }
}
