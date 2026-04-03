<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganizationDetailsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('organization_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained('organizations')->onDelete('cascade');
            $table->string('provider_id')->nullable();
            $table->string('organization_logo')->nullable();
            $table->string('organization_name')->nullable();
            $table->string('prefix')->nullable();
            $table->string('select_platform')->nullable();
            $table->boolean('api_access')->default('0')->comment('0 = disallow, 1 = allow');
            $table->string('login_token')->nullable();
            $table->string('api_token')->nullable();
            $table->string('max_activation_length')->nullable()->comment('-1 = unlimited');
            $table->string('device_activation_limit')->nullable();
            $table->string('void_payment_in')->nullable();
            $table->boolean('custom_charges')->default('0')->comment('0 = disallow, 1 = allow');
            $table->boolean('custom_subscription')->default('0')->comment('0 = disallow, 1 = allow');
            $table->boolean('device_slots')->default('0')->comment('0 = disallow, 1 = allow');
            $table->boolean('device_linking')->default('0')->comment('0 = disallow, 1 = allow');
            $table->string('link_code_expiration')->nullable();
            $table->boolean('active_toa')->default('0')->comment('0 = disallow, 1 = allow');
            $table->boolean('subscription_activation')->default('0')->comment('0 = disallow, 1 = allow');
            $table->boolean('subscription_prorating')->default('0')->comment('0 = disallow, 1 = allow');
            $table->boolean('content_add_on_prorating')->default('0')->comment('0 = disallow, 1 = allow');
            $table->boolean('voucher_subscribers')->default('0')->comment('0 = disallow, 1 = allow');
            $table->string('expired_voucher_removal')->nullable();
            $table->string('voucher_slots')->nullable();
            $table->boolean('payment_service_system_default')->default(0)->comment('0 for not default, 1 system default');
            $table->boolean('payment_service_default')->default(0)->comment('0 for not default, 1 system default');
            $table->boolean('currency_converter_system_default')->nullable()->comment('0 for not default, 1 system default');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('organization_details');
    }
}
