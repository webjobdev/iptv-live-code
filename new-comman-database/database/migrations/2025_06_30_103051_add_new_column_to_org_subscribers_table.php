<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumnToOrgSubscribersTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('org_subscribers', function (Blueprint $table) {
            $table->foreignId('subscription_and_payments_id')
                ->nullable()
                ->after('organization_id')
                ->constrained('org_subscription_and_payments')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('org_subscribers', function (Blueprint $table) {
            //
        });
    }
}
