<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Contus\Subscribers\Model\OrgSubscriberPayment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrgSubscriberPaymentSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        DB::table('org_subscriber_payment')->insert([
            [
                'start_at'      => Carbon::now()->subDays(10),
                'end_at'        => Carbon::now()->addDays(20),
                'autopay'       => '1',
                'subscription'  => 'Monthly Plan',
                'devices'       => '3',
                'is_active'     => '1',
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
            [
                'start_at'      => Carbon::now()->subDays(30),
                'end_at'        => Carbon::now()->addDays(60),
                'autopay'       => '0',
                'subscription'  => 'Annual Plan',
                'devices'       => '5',
                'is_active'     => '0',
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
        ]);
    }
}
