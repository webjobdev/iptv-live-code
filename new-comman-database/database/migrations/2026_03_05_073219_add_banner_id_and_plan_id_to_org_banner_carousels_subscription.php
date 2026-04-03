<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBannerIdAndPlanIdToOrgBannerCarouselsSubscription extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('org_banner_carousels_subscription', function (Blueprint $table) {
            $table->unsignedBigInteger('banner_id')->after('id')->nullable();
            $table->unsignedBigInteger('plan_id')->after('banner_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('org_banner_carousels_subscription', function (Blueprint $table) {
            //
        });
    }
}
