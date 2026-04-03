<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrganizationDetailsSeeder extends Seeder {
    public function run() {
        DB::table('organization_details')->insert([
            'organization_logo' => 'logo.png',
            'organization_name' => 'Test Org Name',
            'prefix' => 'TST',
            'select_platform' => 'web',
            'api_access' => 1,
            'login_token' => 'logintoken123',
            'api_token' => 'apitoken123',
            'max_activation_length' => '30',
            'device_activation_limit' => '5',
            'void_payment_in' => '7',
            'custom_charges' => 1,
            'custom_subscription' => 1,
            'device_slots' => 1,
            'device_linking' => 0,
            'link_code_expiration' => '60',
            'active_toa' => 1,
            'subscription_activation' => 1,
            'subscription_prorating' => 0,
            'content_add_on_prorating' => 0,
            'voucher_subscribers' => 0,
            'expired_voucher_removal' => '15',
            'voucher_slots' => '3',
            'organization_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
