<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganizationPaymentsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('organization_payments', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('plan_id');
            $table->bigInteger('organization_id')->nullable();
            $table->string('payment_id');
            $table->string('currency');
            $table->string('method');
            $table->longText('payload');
            $table->string('amount');
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('organization_payments');
    }
}
