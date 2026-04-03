<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class LiveEventSeeder extends Seeder
{
    public function run()
    {
        $events = [
            [
                'title' => 'UEFA Champions League – Quarter Final',
                'slug' => Str::slug('UEFA Champions League – Quarter Final'),
                'description' => 'Experience the thrill of Europe’s biggest club football tournament as elite teams compete in the Quarter Final stage. Enjoy live action, expert commentary, and every key moment.',
                'hls_playlist_url' => 'https://demo.unified-streaming.com/k8s/features/stable/video/tears-of-steel/tears-of-steel.mp4/.m3u8',
                'age_limit' => '16+',
            ],
            [
                'title' => 'NBA Live – Lakers vs Warriors',
                'slug' => Str::slug('NBA Live – Lakers vs Warriors'),
                'description' => 'Watch the NBA giants clash live as the Los Angeles Lakers take on the Golden State Warriors in a high-intensity showdown.',
                'hls_playlist_url' => 'https://stream.example.com/live/nba_lakers_warriors/playlist.m3u8',
                'age_limit' => '13+',
            ],
            [
                'title' => 'ICC World Cup – Semi Final',
                'slug' => Str::slug('ICC World Cup – Semi Final'),
                'description' => 'Don’t miss the excitement of the ICC World Cup Semi Final featuring top international teams battling for a spot in the final.',
                'hls_playlist_url' => 'https://stream.example.com/live/icc_wc_semi/playlist.m3u8',
                'age_limit' => '7+',
            ],
            [
                'title' => 'Global Music Concert – Live',
                'slug' => Str::slug('Global Music Concert – Live'),
                'description' => 'Enjoy a spectacular live music concert featuring international artists, stunning visuals, and an unforgettable performance.',
                'hls_playlist_url' => 'https://cdn.demo-stream.net/live/global_music/index.m3u8',
                'age_limit' => '7+',
            ],
            [
                'title' => 'WWE Monday Night RAW',
                'slug' => Str::slug('WWE Monday Night RAW'),
                'description' => 'Witness non-stop wrestling action as WWE superstars clash live in Monday Night RAW.',
                'hls_playlist_url' => 'https://media.testtv.io/live/wwe_raw/master.m3u8',
                'age_limit' => '16+',
            ],
        ];

        foreach ($events as $event) {
            DB::table('videos')->insert([
                'title' => $event['title'],
                'slug' => $event['slug'],
                'description' => $event['description'],
                'video_duration' => '0:00',
                'is_hls' => 1,
                'hls_playlist_url' => $event['hls_playlist_url'],
                'is_live' => 3,
                'scheduledStartTime' => Carbon::now()->addDays(2),
                'scheduledEndTime' => Carbon::now()->addDays(2)->addHours(2),
                'recordingStartTime' => Carbon::now()->addDays(2),
                'recordingEndTime' => Carbon::now()->addDays(2)->addHours(2),
                'available_until' => Carbon::now()->addDays(3),
                'days' => 1,
                'organization' => 1,
                'drm_type' => 'EZDRM',
                'drm_profile' => 1,
                'playback_token' => 1,
                'policy' => 1,
                'streaming_provider' => 'wowza',
                'live_streaming_provider' => 'webrtc',
                'age_limit' => $event['age_limit'],
                'catch_up_status' => 1,
                'live_rewind_status' => 1,
                'is_active' => 1,
                'is_premium' => 0,
                'is_subscription' => 0,
                'price' => 0.00,
                'creator_id' => 1,
                'updator_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
