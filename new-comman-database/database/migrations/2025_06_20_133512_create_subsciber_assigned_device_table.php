<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubsciberAssignedDeviceTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('subscriber_assigned_device', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscriber_id')->constrained('org_subscribers')->onDelete('cascade');
            $table->foreignId('device_id')->nullable()->constrained('org_subscriber_devices')->onDelete('cascade');
            $table->foreignId('subscription_and_payments_id')->nullable()->constrained('org_subscription_and_payments')->onDelete('cascade');
            $table->string('price')->nullable();
            // $table->string('device_name')->nullable();
            // $table->string('activation_error')->nullable();
            $table->string('product_status')->nullable();
            $table->string('is_active')->nullable()->comment("0 = Inactive, 1 = Active");
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('subsciber_assigned_device');
    }
}
