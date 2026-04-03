<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DashboardConfiguration extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('general_settings')->insert([
            // Subscriber Graphs
            [
                'key' => 'number_of_active_subscriber',
                'value' => '1',
                'category' => 'dashboard_configuration',
                'type' => 'boolean',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Payment Graphs
            [
                'key' => 'transactions_of_payment_service',
                'value' => '1',
                'category' => 'dashboard_configuration',
                'type' => 'boolean',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'based_on',
                'value' => 'by_type',
                'category' => 'dashboard_configuration',
                'type' => 'radio',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'payment_system_type',
                'value' => json_encode([
                    'cash' => true,
                    'Authorize.net' => true,
                    'Check' => true,
                    'External Payment' => true,
                    'Gr4vy' => true,
                    '2C2P' => true,
                    'TrueMoney' => true,
                ]),
                'category' => 'dashboard_configuration',
                'type' => 'json',
                'created_at' => now(),
                'updated_at' => now(),
            ],


            // Payment Information Card
            [
                'key' => 'autopayment_amount',
                'value' => '1',
                'category' => 'dashboard_configuration',
                'type' => 'boolean',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'amount_of_cash_payment',
                'value' => '1',
                'category' => 'dashboard_configuration',
                'type' => 'boolean',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'amount_of_check_payment',
                'value' => '1',
                'category' => 'dashboard_configuration',
                'type' => 'boolean',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'amount_of_2c2p_payment',
                'value' => '1',
                'category' => 'dashboard_configuration',
                'type' => 'boolean',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'amount_of_true_money_payment',
                'value' => '1',
                'category' => 'dashboard_configuration',
                'type' => 'boolean',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'amount_of_total_payment',
                'value' => '1',
                'category' => 'dashboard_configuration',
                'type' => 'boolean',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'amount_of_authorize_net_payment',
                'value' => '1',
                'category' => 'dashboard_configuration',
                'type' => 'boolean',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'amount_of_external_payment',
                'value' => '1',
                'category' => 'dashboard_configuration',
                'type' => 'boolean',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'amount_of_gr4avy_payment',
                'value' => '1',
                'category' => 'dashboard_configuration',
                'type' => 'boolean',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
