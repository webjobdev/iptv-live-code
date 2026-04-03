<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrgSubscriptionAndPaymentsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('org_subscription_and_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscriber_id')->constrained('org_subscribers')->onDelete('cascade');
            $table->string('product_type')->nullable();
            $table->string('activation')->nullable();
            $table->string('subscription')->nullable();
            $table->string('day_month_type')->nullable();
            $table->string('adjust_length')->nullable();
            $table->string('length_type')->nullable();
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->string('device')->nullable();
            $table->string('subscribable_type')->nullable();
            $table->string('payment_service')->nullable();
            $table->string('payment_status')->nullable();
            $table->string('cash_location')->nullable();
            $table->string('payment_currency')->nullable();
            $table->string('total')->nullable();
            $table->string('accessory')->nullable();
            $table->string('bundels')->nullable();
            $table->string('prorate_subsciption')->nullable()->comment("0 = off, 1 = on");
            $table->string('terms_of_agreement')->nullable()->comment("0 = disagree, 1 = agree");
            $table->string('is_active')->nullable()->comment("0 = in_active, 1 = active");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('org_subscription_and_payments');
    }
}
