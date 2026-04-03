<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TvCategorySeeder extends Seeder
{
    public function run()
    {
        $organizationId = 1;

        $categories = [
            'News',
            'Sports',
            'Movies',
            'Entertainment',
            'Kids',
            'Music',
            'Lifestyle',
            'Documentary',
            'Religious',
            'International',
        ];

        $order = 1;

        foreach ($categories as $categoryName) {

            DB::table('tv_category')->insert([
                'organization' => $organizationId,
                'tv_categorie_name' => $categoryName,
                'category_name' => strtoupper($categoryName),
                'categorie_id' => uniqid('TV_CAT_'),
                'category_order' => $order++,

                // Must be valid JSON (as per CHECK constraint)
                'channel' => json_encode([]),

                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
