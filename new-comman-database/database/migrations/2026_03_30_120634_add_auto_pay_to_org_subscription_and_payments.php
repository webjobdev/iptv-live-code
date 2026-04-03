<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAutoPayToOrgSubscriptionAndPayments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('org_subscription_and_payments', function (Blueprint $table) {
            $table->boolean('auto_pay')->default(0)->nullable()->comment('0 = No, 1 = Yes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('org_subscription_and_payments', function (Blueprint $table) {
            $table->dropColumn('auto_pay');
        });
    }
}
