<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumnToOrgSubscriptionAndPaymentsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('org_subscription_and_payments', function (Blueprint $table) {
            $table->foreignId('subscriber_payment_id')->after('subscriber_id')->nullable()->constrained('org_subscriber_payment')->onDelete('cascade');
            $table->string('custom_charge_comment')->after('accessory')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('org_subscription_and_payments', function (Blueprint $table) {
            //
        });
    }
}
