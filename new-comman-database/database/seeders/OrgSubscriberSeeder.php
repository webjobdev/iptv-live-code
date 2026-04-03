<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrgSubscriberSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $faker = Faker::create();
        $data = [];

        for ($i = 0; $i < 10; $i++) {
            $data[] = [
                // 'organization_id'   => $faker->numberBetween(1, 100),
                'organization_name' => $faker->company,
                'account_number'    => $faker->bankAccountNumber,
                'pin_code'          => $faker->numerify('####'),
                'user_name'         => strtolower($faker->userName),
                'password'          => bcrypt('password123'), // or use Hash::make
                'first_name'        => $faker->firstName,
                'last_name'         => $faker->lastName,
                'email'             => $faker->unique()->safeEmail,
                'phone_number'      => $faker->phoneNumber,
                'address'           => $faker->streetAddress,
                'city'              => $faker->city,
                'zip_code'          => $faker->postcode,
                'country'           => $faker->country,
                'state'             => $faker->state,
                'language'          => $faker->randomElement(['en', 'es', 'fr', 'de']),
                'date_of_birth'     => $faker->date('Y-m-d', '-18 years'), // 18+ only
                'timezone'          => $faker->timezone,
                'created_at'        => now(),
                'updated_at'        => now(),
            ];
        }

        DB::table('org_subscribers')->insert($data);
    }
}
