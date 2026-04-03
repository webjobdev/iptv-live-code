<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class SubscriberCreditCardSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $faker = Faker::create();
        $data = [];

        for ($i = 0; $i < 2; $i++) {
            $data[] = [
                // 'subscriber_id'     => $faker->numberBetween(1, 2),
                'subscriber_id'     => 1,
                'profile_name'      => $faker->randomElement(['demo', 'test', 'test1', 'test2', 'demo1', 'user1', 'example']),
                'security_type'     => $faker->randomElement(['local', 'authorized']),
                'card_type'         => $faker->randomElement(['visa', 'mastercard', 'jcb', 'american express']),
                'card_number'       => $faker->creditCardNumber,
                'expiration_month'  => $faker->numberBetween(01, 12),
                'expiration_year'   => $faker->numberBetween(date('Y'), date('Y') + 25),
                'cvv'               => $faker->numberBetween(100, 999),
                'billing_address'   => $faker->numberBetween(0, 1),
                'first_name'        => $faker->firstName,
                'last_name'         => $faker->lastName,
                'email'             => $faker->safeEmail,
                'phone_number'      => $faker->phoneNumber,
                'address'           => $faker->streetAddress,
                'city'              => $faker->city,
                'zip_code'          => $faker->postcode,
                'country'           => $faker->country,
                'state'             => $faker->state,
                'is_active'         => $faker->numberBetween(0, 1),
                'created_at'        => now(),
                'updated_at'        => now(),
            ];
        }

        DB::table('org_subscriber_creditcard')->insert($data);
    }
}
