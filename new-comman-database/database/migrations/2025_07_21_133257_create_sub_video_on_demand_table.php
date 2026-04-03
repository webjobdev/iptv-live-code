<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubVideoOnDemandTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_video_on_demand', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscriber_id')
                ->nullable()
                ->constrained('org_subscribers')
                ->onDelete('cascade');
            $table->string('video_on_demand_list')->nullable();
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
        Schema::dropIfExists('sub_video_on_demand');
    }
}
