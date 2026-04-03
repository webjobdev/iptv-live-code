<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsActiveOnOrgMonetizationPlanss extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('org_monetization_planss', function (Blueprint $table) {
           $table->boolean('is_active')->default(0)->comment('0: Inactive, 1: Active');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('org_monetization_planss', function (Blueprint $table) {
            //
        });
    }
}
