<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;

class TvShowSeeder extends Seeder
{
    public function run()
    {
        $tvShows = [
            [
                'title' => 'Breaking Bad',
                'description' => 'A chemistry teacher diagnosed with cancer turns to manufacturing drugs to secure his family’s future.',
                'directors' => 'Vince Gilligan',
                'presenter' => 'Bryan Cranston, Aaron Paul',
                'category' => ['Action', 'Drama', 'Crime'],
                'age_limit' => '16+',
            ],
            [
                'title' => 'Game of Thrones',
                'description' => 'Noble families vie for control of the Iron Throne in the mythical land of Westeros.',
                'directors' => 'David Benioff, D.B. Weiss',
                'presenter' => 'Emilia Clarke, Kit Harington',
                'category' => ['Drama', 'Fantasy', 'Action'],
                'age_limit' => '18+',
            ],
            [
                'title' => 'Stranger Things',
                'description' => 'A group of kids uncover supernatural mysteries in their small town.',
                'directors' => 'The Duffer Brothers',
                'presenter' => 'Millie Bobby Brown, Finn Wolfhard',
                'category' => ['Sci-Fi', 'Thriller'],
                'age_limit' => '13+',
            ],
            [
                'title' => 'The Walking Dead',
                'description' => 'Survivors struggle to stay alive in a world overrun by zombies.',
                'directors' => 'Frank Darabont',
                'presenter' => 'Andrew Lincoln, Norman Reedus',
                'category' => ['Drama', 'Horror'],
                'age_limit' => '18+',
            ],
            [
                'title' => 'Peaky Blinders',
                'description' => 'A gangster family rises to power in post–World War I Birmingham.',
                'directors' => 'Steven Knight',
                'presenter' => 'Cillian Murphy, Paul Anderson',
                'category' => ['Crime', 'Drama'],
                'age_limit' => '16+',
            ],
            [
                'title' => 'The Boys',
                'description' => 'A group of vigilantes set out to take down corrupt superheroes.',
                'directors' => 'Eric Kripke',
                'presenter' => 'Karl Urban, Antony Starr',
                'category' => ['Action', 'Drama'],
                'age_limit' => '18+',
            ],
            [
                'title' => 'Narcos',
                'description' => 'The rise and fall of drug kingpin Pablo Escobar.',
                'directors' => 'Chris Brancato',
                'presenter' => 'Pedro Pascal, Wagner Moura',
                'category' => ['Crime', 'Drama'],
                'age_limit' => '16+',
            ],
            [
                'title' => 'Dark',
                'description' => 'A family saga with a supernatural twist set in a German town.',
                'directors' => 'Baran bo Odar',
                'presenter' => 'Louis Hofmann, Oliver Masucci',
                'category' => ['Sci-Fi', 'Thriller'],
                'age_limit' => '16+',
            ],
            [
                'title' => 'House of the Dragon',
                'description' => 'The story of House Targaryen set 200 years before Game of Thrones.',
                'directors' => 'Ryan Condal',
                'presenter' => 'Paddy Considine, Emma D’Arcy',
                'category' => ['Drama', 'Fantasy'],
                'age_limit' => '18+',
            ],
        ];

        foreach ($tvShows as $show) {
            DB::table('tv_shows')->insert([
                'title' => $show['title'],
                // 'slug' => Str::slug($show['title']),
                'description' => $show['description'],
                'release_date' => Carbon::now(),
                'scheduled_publishing' => 1,
                'scheduled_time' => Carbon::now()->addMinutes(10),
                'expire_scheduled_time' => Carbon::now()->addDays(1),
                'publish_now' => 1,
                'publish_date' => Carbon::now(),
                'directors' => $show['directors'],
                'presenter' => $show['presenter'],
                'organization' => 1,
                'trailer_url' => 'https://content.jwplatform.com/manifests/yp34SRmf.m3u8',
                'playback_token' => 1,
                'policy' => 1,
                'category' => json_encode($show['category']),
                'age_rating' => 0,
                'is_active' => 1,
                'age_limit' => $show['age_limit'],
                'is_parental' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
