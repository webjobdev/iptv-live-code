<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SeriesCategorySeeder extends Seeder
{
    public function run()
    {
        $organizationId = 1;

        $categories = [
            'Action'        => ['Military', 'Superhero'],
            'Drama'         => ['Family Drama', 'Political Drama'],
            'Crime'         => ['Investigation', 'Mafia'],
            'Sci-Fi'        => ['Space', 'Time Travel'],
            'Thriller'      => ['Psychological', 'Mystery'],
            'Romance'       => ['Romantic Series'],
            'Comedy'        => ['Sitcom', 'Stand-up'],
            'Fantasy'       => ['Mythology'],
            'Animation'     => ['Kids Series', 'Anime'],
            'Documentary'   => ['True Crime', 'Nature'],
        ];

        $order = 1;

        foreach ($categories as $categoryName => $subCategories) {

            // Insert Main Series Category
            $categoryId = DB::table('series_category')->insertGetId([
                'organization'            => $organizationId,
                'series_categorie_name'   => $categoryName,
                'category_name'           => strtoupper($categoryName),
                'categorie_id'            => uniqid('SER_CAT_'),
                'category_order'          => $order++,
                'created_at'              => Carbon::now(),
                'updated_at'              => Carbon::now(),
            ]);

            // Insert Series Sub Categories
            $subOrder = 1;
            foreach ($subCategories as $subCategory) {
                DB::table('series_category_sub_category')->insert([
                    'categorie_id'       => $categoryId,
                    'sub_category_name'  => $subCategory,
                    'category_order'     => $subOrder++,
                    'created_at'         => Carbon::now(),
                    'updated_at'         => Carbon::now(),
                ]);
            }
        }
    }
}
