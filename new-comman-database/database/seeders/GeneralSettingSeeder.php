<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GeneralSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('general_settings')->truncate();

        DB::table('general_settings')->insert([
            // Email Server Settings
            [
                'key' => 'email_address',
                'value' => 'iptv@gmail.org',
                'category' => 'email_setting',
                'type' => 'string'
            ],
            [
                'key' => 'password',
                'value' => '00000000',
                'category' => 'email_setting',
                'type' => 'string'
            ],
            [
                'key' => 'smtp_host',
                'value' => 'smtp.gmail.com',
                'category' => 'email_setting',
                'type' => 'string'
            ],
            [
                'key' => 'smtp_port',
                'value' => '465',
                'category' => 'email_setting',
                'type' => 'string'
            ],


            // Payment Settings
            [
                'key' => 'max_activation_length',
                'value' => '25',
                'type' => 'string',
                'category' => 'payment_setting'
            ],
            [
                'key' => 'max_activation_length_unlimited',
                'value' => 'false',
                'type' => 'check',
                'category' => 'payment_setting'
            ],
            [
                'key' => 'device_activation_limit',
                'value' => '20',
                'type' => 'string',
                'category' => 'payment_setting'
            ],
            [
                'key' => 'void_payment_in',
                'value' => '2',
                'type' => 'check',
                'category' => 'payment_setting'
            ],
            [
                'key' => 'disallow_void',
                'value' => 'false',
                'type' => 'check',
                'category' => 'payment_setting'
            ],
            [
                'key' => 'custom_charges',
                'value' => 'allow',
                'type' => 'radio',
                'category' => 'payment_setting'
            ],
            [
                'key' => 'custom_subscription',
                'value' => 'allow',
                'type' => 'radio',
                'category' => 'payment_setting'
            ],
            [
                'key' => 'device_slots',
                'value' => 'allow',
                'type' => 'radio',
                'category' => 'payment_setting'
            ],
            [
                'key' => 'device_linkings',
                'value' => 'allow',
                'type' => 'radio',
                'category' => 'payment_setting'
            ],
            [
                'key' => 'link_code_expiration',
                'value' => '2',
                'type' => 'string',
                'category' => 'payment_setting'
            ],
            [
                'key' => 'active_toa',
                'value' => 'false',
                'type' => 'radio',
                'category' => 'payment_setting'
            ],
            [
                'key' => 'subscription_activation',
                'value' => 'false',
                'type' => 'radio',
                'category' => 'payment_setting'
            ],
            [
                'key' => 'subscription_prorating',
                'value' => 'false',
                'type' => 'radio',
                'category' => 'payment_setting'
            ],
            [
                'key' => 'content_add_on_prorating',
                'value' => 'false',
                'type' => 'radio',
                'category' => 'payment_setting'
            ],
            [
                'key' => 'expired_voucher_removal',
                'value' => 'false',
                'type' => 'string',
                'category' => 'payment_setting'
            ],
            [
                'key' => 'voucher_subscribers',
                'value' => 'false',
                'type' => 'radio',
                'category' => 'payment_setting'
            ],
            [
                'key' => 'voucher_slots',
                'value' => 'false',
                'type' => 'string',
                'category' => 'payment_setting'
            ],

            // Multi Tenant Settings
            [
                'key' => 'multi_tenant_mode',
                'value' => 'false',
                'type' => 'toggle',
                'category' => 'multi_tenant_setting'
            ],

            // login options
            [
                'key' => 'guest_mode',
                'value' => 'false',
                'type' => 'toggle',
                'category' => 'multi_tenant_setting'
            ],
            [
                'key' => 'guest_organization',
                'value' => 'Guest Org',
                'type' => 'select',
                'category' => 'multi_tenant_setting'
            ],
            [
                'key' => 'guest_subscription',
                'value' => 'Guest Subscription',
                'type' => 'select',
                'category' => 'multi_tenant_setting'
            ],
            [
                'key' => 'in_app_registration',
                'value' => 'true',
                'type' => 'toggle',
                'category' => 'multi_tenant_setting'
            ],
            [
                'key' => 'default_organization',
                'value' => 'Test Org',
                'type' => 'select',
                'category' => 'multi_tenant_setting'
            ],
            [
                'key' => 'default_subscription',
                'value' => 'Test Org',
                'type' => 'select',
                'category' => 'multi_tenant_setting'
            ],
            [
                'key' => 'code_expiration_time',
                'value' => '2',
                'type' => 'string',
                'category' => 'multi_tenant_setting'
            ],
            [
                'key' => 'code_expiration_time_type',
                'value' => 'hours',
                'type' => 'select',
                'category' => 'multi_tenant_setting'
            ],

        ]);
    }
}
