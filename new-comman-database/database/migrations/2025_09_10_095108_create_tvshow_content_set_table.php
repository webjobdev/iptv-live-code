<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTvshowContentSetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tvshow_content_set', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('organization_id')->nullable();
            $table->string('name')->nullable();
            $table->string('item_type')->nullable();
            $table->json('assigned_tv_show')->nullable();
            $table->json('assigned_tv_show_season')->nullable();
            $table->json('assigned_tv_show_episode')->nullable();
            $table->string('currency')->nullable();
            $table->string('monitization_type_buy')->comment("1 For Buy")->nullable();
            $table->string('payment_method_buy')->comment("0 For Per Bundel, 1 For Per Item")->nullable();
            $table->float('buy_price')->nullable();
            $table->string('monitization_type_rent')->comment("0 For Rent")->nullable();
            $table->string('payment_method_rent')->comment("0 For Per Bundel, 1 For Per Item")->nullable();
            $table->float('rent_price')->nullable();
            $table->string('period')->nullable();
            $table->string('period_type')->nullable();
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
        Schema::dropIfExists('tvshow_content_set');
    }
}
