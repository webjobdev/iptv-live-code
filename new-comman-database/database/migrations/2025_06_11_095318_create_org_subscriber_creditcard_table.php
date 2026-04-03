<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrgSubscriberCreditcardTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('org_subscriber_creditcard', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscriber_id')->constrained('org_subscribers')->onDelete('cascade');
            $table->string('profile_name')->nullable();
            $table->string('security_type')->nullable();
            $table->string('card_type')->nullable();
            $table->string('card_number')->nullable();
            $table->string('expiration_month')->nullable();
            $table->string('expiration_year')->nullable();
            $table->string('cvv')->nullable();
            $table->string('billing_address')->nullable()->comment("0 = subscriber's billing, 1 = custom");
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('country')->nullable();
            $table->string('state')->nullable();
            $table->string('is_active')->nullable()->comment("0 = in_active, 1 = active");
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
        Schema::dropIfExists('org_subscriber_creditcard');
    }
}
