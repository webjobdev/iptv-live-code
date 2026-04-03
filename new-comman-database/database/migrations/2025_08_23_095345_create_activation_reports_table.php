<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivationReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activation_reports', function (Blueprint $table) {
            $table->id();
            $table->string('report_name')->nullable();
            $table->string('report_period')->nullable();
            $table->foreignId('organization')->nullable()->constrained('organization_details')->onDelete('cascade');
            $table->string('users')->nullable();
            $table->boolean('subscription_plan')->comment('0 For Subscription Plan, 1 For Subscription Length');
            $table->boolean('subscription_plan_type')->comment('0 For Daily, 1 For Month');
            $table->date('subscription_length_from_date')->nullable();
            $table->date('subscription_length_to_date')->nullable();
            $table->string('payment_service')->nullable();
            $table->boolean('autopay')->default(0)->comment("0 For Not AutoPay, 1 For AutoPay");
            $table->string('available_plan')->nullable();
            $table->boolean('generate')->default(0)->comment("0 For Not Generated, 1 For Generated");
            $table->unsignedBigInteger('created_by');
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
        Schema::dropIfExists('activation_reports');
    }
}
