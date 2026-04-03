<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class VideoOnDemandSeeder extends Seeder
{
    public function run()
    {
        $movies = [
            [
                'title' => 'Inception',
                'release_year' => 2010,
                'video_duration' => ['hour' => '02', 'minute' => '28', 'second' => '00'],
                'description' => 'A skilled thief enters the dreams of others to steal secrets, but faces his toughest mission yet.',
                'directors' => 'Christopher Nolan',
                'presenter' => 'Leonardo DiCaprio, Joseph Gordon-Levitt',
                'video_quality' => 'FHD',
                'age_limit' => '13+',
                'category' => ['SCI-FI', 'THRILLER'],
            ],
            [
                'title' => 'Interstellar',
                'release_year' => 2014,
                'video_duration' => ['hour' => '02', 'minute' => '49', 'second' => '00'],
                'description' => 'A group of explorers travel through a wormhole in space to ensure humanity’s survival.',
                'directors' => 'Christopher Nolan',
                'presenter' => 'Matthew McConaughey, Anne Hathaway',
                'video_quality' => 'UHD',
                'age_limit' => '13+',
                'category' => ['SCI-FI'],
            ],
            [
                'title' => 'The Dark Knight',
                'release_year' => 2008,
                'video_duration' => ['hour' => '02', 'minute' => '32', 'second' => '00'],
                'description' => 'Batman faces the Joker, a criminal mastermind who plunges Gotham into chaos.',
                'directors' => 'Christopher Nolan',
                'presenter' => 'Christian Bale, Heath Ledger',
                'video_quality' => 'FHD',
                'age_limit' => '16+',
                'category' => ['ACTION'],
            ],
            [
                'title' => 'Gladiator',
                'release_year' => 2000,
                'video_duration' => ['hour' => '02', 'minute' => '35', 'second' => '00'],
                'description' => 'A former Roman General sets out to exact vengeance against a corrupt emperor.',
                'directors' => 'Ridley Scott',
                'presenter' => 'Russell Crowe, Joaquin Phoenix',
                'video_quality' => 'HD',
                'age_limit' => '16+',
                'category' => ['DRAMA'],
            ],
            [
                'title' => 'Titanic',
                'release_year' => 1997,
                'video_duration' => ['hour' => '03', 'minute' => '14', 'second' => '00'],
                'description' => 'A love story unfolds aboard the ill-fated RMS Titanic.',
                'directors' => 'James Cameron',
                'presenter' => 'Leonardo DiCaprio, Kate Winslet',
                'video_quality' => 'HD',
                'age_limit' => '13+',
                'category' => ['ROMANCE'],
            ],
            [
                'title' => 'Jurassic World',
                'release_year' => 2015,
                'video_duration' => ['hour' => '02', 'minute' => '04', 'second' => '00'],
                'description' => 'A new dinosaur theme park faces disaster when genetically modified creatures escape.',
                'directors' => 'Colin Trevorrow',
                'presenter' => 'Chris Pratt, Bryce Dallas Howard',
                'video_quality' => 'FHD',
                'age_limit' => '13+',
                'category' => ['ACTION'],
            ],
            [
                'title' => 'The Matrix',
                'release_year' => 1999,
                'video_duration' => ['hour' => '02', 'minute' => '16', 'second' => '00'],
                'description' => 'A hacker discovers the truth about reality and his role in the war against machines.',
                'directors' => 'The Wachowskis',
                'presenter' => 'Keanu Reeves, Laurence Fishburne',
                'video_quality' => 'FHD',
                'age_limit' => '16+',
                'category' => ['SCI-FI'],
            ],
            [
                'title' => 'Avengers: Endgame',
                'release_year' => 2019,
                'video_duration' => ['hour' => '03', 'minute' => '01', 'second' => '00'],
                'description' => 'The Avengers assemble once more to undo Thanos’ actions and restore balance.',
                'directors' => 'Anthony Russo, Joe Russo',
                'presenter' => 'Robert Downey Jr., Chris Evans',
                'video_quality' => 'UHD',
                'age_limit' => '13+',
                'category' => ['ACTION'],
            ],
            [
                'title' => 'Forrest Gump',
                'release_year' => 1994,
                'video_duration' => ['hour' => '02', 'minute' => '22', 'second' => '00'],
                'description' => 'The extraordinary life journey of a simple man with a big heart.',
                'directors' => 'Robert Zemeckis',
                'presenter' => 'Tom Hanks, Robin Wright',
                'video_quality' => 'HD',
                'age_limit' => '7+',
                'category' => ['DRAMA'],
            ],
        ];

        foreach ($movies as $movie) {

            $duration = $movie['video_duration'];
            $timeString = "{$duration['hour']}:{$duration['minute']}:{$duration['second']}";

            DB::table('video_on_demand')->insert([
                'title' => $movie['title'],
                'release_year' => $movie['release_year'],
                'description' => $movie['description'],
                'video_quality' => $movie['video_quality'],

                // duration fields
                'timeParts' => json_encode($duration),
                // 'timeString' => $timeString,

                'directors' => $movie['directors'],
                'presenter' => $movie['presenter'],
                'category' => json_encode($movie['category']),

                'organization' => 1,
                'streaming_url' => 'https://cdn.flowplayer.com/demo/hls/sample/playlist.m3u8',
                'trailer_url' => 'https://content.jwplatform.com/manifests/yp34SRmf.m3u8',

                'drm_type' => 'EZDRM',
                'drm_profile' => 1,
                'playback_token' => 2,
                'policy' => 3,

                'scheduled_publishing' => 1,
                'publish_now' => 1,
                'publish_date' => Carbon::now(),
                'scheduled_time' => Carbon::now()->addDay(),
                'expire_scheduled_time' => Carbon::now()->addDays(2),

                'is_active' => 1,
                'age_rating' => 1,
                'age_limit' => $movie['age_limit'],
                'is_parental' => 1,

                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
