<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TvShowSeasonSeeder extends Seeder
{
    public function run()
    {
        $seasons = [
            [
                'title' => 'Season 3',
                'season_number' => 3,
                'tv_show_id' => 5, // Money Heist
                'description' => 'A criminal mastermind assembles a team to execute the biggest heist in Spanish history.',
                'directors' => 'Álex Pina',
                'presenter' => 'Úrsula Corberó, Álvaro Morte, Itziar Ituño',
            ],
            [
                'title' => 'Season 3',
                'season_number' => 3,
                'tv_show_id' => 6, // Breaking Bad
                'description' => 'A chemistry teacher turns to crime after a terminal diagnosis.',
                'directors' => 'Vince Gilligan',
                'presenter' => 'Bryan Cranston, Aaron Paul',
            ],
            [
                'title' => 'Season 3',
                'season_number' => 3,
                'tv_show_id' => 7, // Game of Thrones
                'description' => 'Noble families fight for control of the Iron Throne.',
                'directors' => 'David Benioff, D.B. Weiss',
                'presenter' => 'Emilia Clarke, Kit Harington',
            ],
            [
                'title' => 'Season 3',
                'season_number' => 3,
                'tv_show_id' => 8, // Stranger Things
                'description' => 'A small town uncovers supernatural mysteries.',
                'directors' => 'The Duffer Brothers',
                'presenter' => 'Millie Bobby Brown, Finn Wolfhard',
            ],
            [
                'title' => 'Season 3',
                'season_number' => 3,
                'tv_show_id' => 9, // The Walking Dead
                'description' => 'Survivors fight to stay alive in a zombie apocalypse.',
                'directors' => 'Frank Darabont',
                'presenter' => 'Andrew Lincoln, Norman Reedus',
            ],
            [
                'title' => 'Season 3',
                'season_number' => 3,
                'tv_show_id' => 10, // Peaky Blinders
                'description' => 'A gangster family rises to power in post-war England.',
                'directors' => 'Steven Knight',
                'presenter' => 'Cillian Murphy, Paul Anderson',
            ],
            [
                'title' => 'Season 3',
                'season_number' => 3,
                'tv_show_id' => 11, // The Boys
                'description' => 'Vigilantes take on corrupt superheroes.',
                'directors' => 'Eric Kripke',
                'presenter' => 'Karl Urban, Antony Starr',
            ],
            [
                'title' => 'Season 3',
                'season_number' => 3,
                'tv_show_id' => 12, // Narcos
                'description' => 'The rise and fall of drug lord Pablo Escobar.',
                'directors' => 'Chris Brancato',
                'presenter' => 'Pedro Pascal, Wagner Moura',
            ],
            [
                'title' => 'Season 3',
                'season_number' => 3,
                'tv_show_id' => 13, // Dark
                'description' => 'A time-travel mystery spanning generations.',
                'directors' => 'Baran bo Odar',
                'presenter' => 'Louis Hofmann, Oliver Masucci',
            ],
            [
                'title' => 'Season 3',
                'season_number' => 3,
                'tv_show_id' => 14, // Dark
                'description' => 'An internal succession war within House Targaryen at the height of its power, 172 years before the birth of Daenerys Targaryen.',
                'directors' => 'Ryan J. Condal, George R.R. Martin',
                'presenter' => 'Matt Smith, Emma DArcy, Olivia Cooke',
            ],
        ];

        foreach ($seasons as $season) {
            DB::table('tv_show_seasons')->insert([
                'title' => $season['title'],
                'season_number' => $season['season_number'],
                'tv_show_id' => $season['tv_show_id'],
                'release_date' => Carbon::now()->subYears(5),
                'description' => $season['description'],
                'is_active' => 1,
                'directors' => $season['directors'],
                'presenter' => $season['presenter'],
                'scheduled_publishing' => 1,
                'publish_now' => 1,
                'publish_date' => Carbon::now(),
                'scheduled_time' => Carbon::now()->addMinutes(10),
                'expire_time_unlimited' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
