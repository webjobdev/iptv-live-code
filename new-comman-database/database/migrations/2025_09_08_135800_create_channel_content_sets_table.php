<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChannelContentSetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('channel_content_sets', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('organization_id')->nullable();
            $table->string('name')->nullable();
            $table->json('assigned_channels')->nullable();
            $table->string('currency')->nullable();
            $table->string('monitization_type')->comment("0 For Rent, 1 For Buy")->nullable();
            $table->string('payment_method')->comment("0 For Per Bundel, 1 For Per Item")->nullable();
            $table->float('price')->nullable();
            $table->string('cover_image')->nullable();
            $table->string('description')->nullable();
            $table->bigInteger('by_user')->nullable();
            $table->string('is_active')->comment("0 For Inactive, 1 For Active")->nullable();
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
        Schema::dropIfExists('channel_content_sets');
    }
}
