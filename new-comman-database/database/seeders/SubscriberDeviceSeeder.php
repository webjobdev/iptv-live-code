<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubscriberDeviceSeeder extends Seeder {
    public function run() {
        $faker = Faker::create();
        $data = [];

        for ($i = 0; $i < 5; $i++) {
            $data[] = [
                // 'subscriber_id' => $faker->numberBetween(1, 2),
                'subscriber_id' => 1,
                'brand_model' => $faker->randomElement([
                    'Samsung Galaxy S21',
                    'Samsung Galaxy S22 Ultra',
                    'Samsung Galaxy A54',
                    'Samsung Galaxy Z Fold 5',
                    'iPhone 13',
                    'iPhone 13 Pro Max',
                    'iPhone 14',
                    'iPhone 14 Plus',
                    'iPhone 14 Pro Max',
                    'iPhone SE (3rd Gen)',
                    'OnePlus 11',
                    'OnePlus Nord CE 3',
                    'OnePlus 10 Pro',
                    'OnePlus 12',
                    'Google Pixel 7',
                    'Google Pixel 7a',
                    'Google Pixel 8',
                    'Google Pixel 8 Pro',
                    'Xiaomi Redmi Note 12',
                    'Xiaomi 13 Pro',
                    'Xiaomi Mi 11 Lite',
                    'Xiaomi Redmi 12C',
                    'Oppo Reno8 Pro',
                    'Oppo Find X5',
                    'Oppo F23 5G',
                    'Vivo V27 Pro',
                    'Vivo X90',
                    'Vivo Y56',
                    'Realme 11 Pro+',
                    'Realme GT Neo 3',
                    'Asus ROG Phone 7',
                    'Nokia G22',
                    'Huawei P50 Pro',
                    'Huawei Mate 50',
                    'Honor Magic5 Pro',
                    'Sony Xperia 1 V',
                    'Sony Xperia 10 IV'
                ]),
                'mac_address'   => $faker->macAddress,
                'serial_number' => 'SN' . $faker->numerify('#########'), // SN followed by 9 digits
                'identifier'    => Str::lower(Str::random(16)),          // 16-char lowercase alphanumeric
                'ip_address'    => $faker->ipv4,
                'city'          => $faker->city,
                'country'       => $faker->country,
                'latitude'      => $faker->latitude,
                'longitude'     => $faker->longitude,
                'status'        => $faker->randomElement(['1', '0']),
                'created_at'    => now(),
                'updated_at'    => now(),
            ];
        }
        DB::table('org_subscriber_devices')->insert($data);
    }
}
