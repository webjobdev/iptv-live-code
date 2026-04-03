<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class VodCategorySeeder extends Seeder
{
    public function run()
    {
        $organizationId = 1;

        $categories = [
            'Action' => ['Superhero', 'War', 'Crime'],
            'Drama' => ['Biography', 'Family', 'Historical'],
            'Sci-Fi' => ['Space', 'Time Travel', 'Artificial Intelligence'],
            'Thriller' => ['Psychological', 'Mystery'],
            'Romance' => ['Romantic Drama'],
            'Comedy' => ['Family Comedy'],
            'Horror' => ['Supernatural', 'Slasher'],
            'Adventure' => ['Fantasy'],
            'Animation' => ['Kids', 'Anime'],
            'Documentary' => ['Nature', 'History'],
        ];

        $order = 1;

        foreach ($categories as $categoryName => $subCategories) {

            // Insert Main Category
            $categoryId = DB::table('vod_category')->insertGetId([
                'organization' => $organizationId,
                'vod_categorie_name' => $categoryName,
                'category_name' => strtoupper($categoryName),
                'categorie_id' => uniqid('CAT_'),
                'category_order' => $order++,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            // Insert Sub Categories
            $subOrder = 1;
            foreach ($subCategories as $subCategory) {
                DB::table('vod_category_sub_category')->insert([
                    'categorie_id' => $categoryId,
                    'sub_category_name' => $subCategory,
                    'category_order' => $subOrder++,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }
    }
}
