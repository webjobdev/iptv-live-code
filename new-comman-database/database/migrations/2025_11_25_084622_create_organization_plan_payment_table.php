<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganizationPlanPaymentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organization_plan_payment', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('orgnization_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable()->comment('subscriber_id');
            $table->unsignedBigInteger('plan_id')->nullable()->comment('monetization plan id');
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('payment_gateway')->nullable();
            $table->string('currency')->nullable();
            $table->string('method')->nullable();
            $table->longText('payload')->nullable();
            $table->float('amount')->nullable();
            $table->string('status')->nullable();
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
        Schema::dropIfExists('organization_plan_payment');
    }
}
