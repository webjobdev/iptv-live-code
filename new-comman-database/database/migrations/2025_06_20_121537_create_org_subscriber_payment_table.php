<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrgSubscriberPaymentTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() { {
            Schema::create('org_subscriber_payment', function (Blueprint $table) {
                $table->id();
                $table->foreignId('subscriber_id')->constrained('org_subscribers')->onDelete('cascade');
                $table->foreignId('subscription_payments_id')->constrained('org_subscription_and_payments')->onDelete('cascade');
                $table->string('payment_id')->nullable();
                $table->string('refund_id')->nullable();
                $table->string('payment_gateway')->nullable();
                $table->string('currency')->nullable();
                $table->string('method')->nullable();
                $table->string('payload')->nullable();
                $table->string('refund_payload')->nullable();
                $table->string('amount')->nullable();
                $table->string('status')->nullable();
                $table->timestamps();
            });
        }
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('org_subscriber_payment');
    }
}
