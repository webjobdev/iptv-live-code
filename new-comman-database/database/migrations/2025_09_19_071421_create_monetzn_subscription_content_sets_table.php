<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMonetznSubscriptionContentSetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('monetzn_subscription_content_sets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('organization_id');
            $table->unsignedBigInteger('montzn_plan_id');
            $table->string('featured_title')->nullable();
            $table->json('channel_id');
            $table->json('channel_add_ons_id');
            $table->json('vod_id');
            $table->json('vod_add_ons_id');
            $table->json('live_event_id');
            $table->json('live_event_add_ons_id');
            $table->json('tv_show_id');
            $table->json('tv_show_add_ons_id');
            $table->boolean('featured_row_status')->nullable()->default('0')->comment("0 For Inactive, 1 For Active");
            $table->boolean('show_in_live')->nullable()->default('0')->comment("0 For Inactive, 1 For Active");
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
        Schema::dropIfExists('monetzn_subscription_content_sets');
    }
}
