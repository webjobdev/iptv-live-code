<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubCustomChannelListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_custom_channel_list', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscriber_id')
                ->nullable()
                ->constrained('org_subscribers')
                ->onDelete('cascade');
            $table->string('channel_list')->nullable();
            $table->dateTime('start_at')->nullable();
            $table->dateTime('end_at')->nullable();
            $table->boolean('is_active')->nullable()->comment("0 = inactive, 1 = active");
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
        Schema::dropIfExists('sub_custom_channel_list');
    }
}
