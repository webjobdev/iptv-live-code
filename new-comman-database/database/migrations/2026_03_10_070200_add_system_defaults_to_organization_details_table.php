<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSystemDefaultsToOrganizationDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('organization_details', function (Blueprint $table) {
            $table->boolean('max_activation_length_system_default')->default(0)->after('max_activation_length')->comment('0 = no, 1 = yes');
            $table->boolean('device_activation_limit_system_default')->default(0)->after('device_activation_limit')->comment('0 = no, 1 = yes');
            $table->boolean('void_payment_in_system_default')->default(0)->after('void_payment_in')->comment('0 = no, 1 = yes');
            $table->boolean('custom_charges_system_default')->default(0)->after('custom_charges')->comment('0 = no, 1 = yes');
            $table->boolean('custom_subscription_system_default')->default(0)->after('custom_subscription')->comment('0 = no, 1 = yes');
            $table->boolean('device_slots_system_default')->default(0)->after('device_slots')->comment('0 = no, 1 = yes');
            $table->boolean('device_linking_system_default')->default(0)->after('device_linking')->comment('0 = no, 1 = yes');
            $table->boolean('link_code_expiration_system_default')->default(0)->after('link_code_expiration')->comment('0 = no, 1 = yes');
            $table->boolean('active_toa_system_default')->default(0)->after('active_toa')->comment('0 = no, 1 = yes');
            $table->boolean('subscription_activation_system_default')->default(0)->after('subscription_activation')->comment('0 = no, 1 = yes');
            $table->boolean('subscription_prorating_system_default')->default(0)->after('subscription_prorating')->comment('0 = no, 1 = yes');
            $table->boolean('content_add_on_prorating_system_default')->default(0)->after('content_add_on_prorating')->comment('0 = no, 1 = yes');
            $table->boolean('voucher_subscribers_system_default')->default(0)->after('voucher_subscribers')->comment('0 = no, 1 = yes');
            $table->boolean('expired_voucher_removal_system_default')->default(0)->after('expired_voucher_removal')->comment('0 = no, 1 = yes');
            $table->boolean('voucher_slots_system_default')->default(0)->after('voucher_slots')->comment('0 = no, 1 = yes');

            // Adding these as they are in the model fillable and UI
            $table->boolean('unlimited')->default(0)->after('max_activation_length_system_default')->comment('0 = no, 1 = yes');
            $table->boolean('disallow_void')->default(0)->after('void_payment_in_system_default')->comment('0 = no, 1 = yes');
            $table->boolean('use_system_default')->default(0)->comment('Global system default toggle');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('organization_details', function (Blueprint $table) {
            $table->dropColumn([
                'max_activation_length_system_default',
                'device_activation_limit_system_default',
                'void_payment_in_system_default',
                'custom_charges_system_default',
                'custom_subscription_system_default',
                'device_slots_system_default',
                'device_linking_system_default',
                'link_code_expiration_system_default',
                'active_toa_system_default',
                'subscription_activation_system_default',
                'subscription_prorating_system_default',
                'content_add_on_prorating_system_default',
                'voucher_subscribers_system_default',
                'expired_voucher_removal_system_default',
                'voucher_slots_system_default',
                'unlimited',
                'disallow_void',
                'use_system_default',
            ]);
        });
    }
}
