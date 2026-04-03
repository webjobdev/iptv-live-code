<?php

namespace Contus\BulkUpload\Api\Controllers\Admin;

use Contus\Base\ApiController;
use Contus\BulkUpload\Repositories\M3uTvShowRepository;
use Contus\Organizations\Model\Organization;
use Contus\Video\Models\SeriesCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class m3uTvShowController extends ApiController
{
    public function __construct(M3uTvShowRepository $m3uTvShowRepository)
    {
        parent::__construct();
        $this->repository = $m3uTvShowRepository;
        $this->repository->setRequestType(static::REQUEST_TYPE);
    }

    public function postAdd(Request $request)
    {
        set_time_limit(0);

        $m3uContent = null;
        $m3uSource = null;

        if ($request->hasFile('m3u_file')) {
            $m3uContent = file_get_contents($request->file('m3u_file')->getRealPath());
            $m3uSource = 'Uploaded File: ' . $request->file('m3u_file')->getClientOriginalName();
        } elseif ($request->filled('m3u_url')) {
            $url = $request->m3u_url;
            if (filter_var($url, FILTER_VALIDATE_URL)) {
                $response = Http::get($url);
                if (!$response->successful()) {
                    return $this->getErrorJsonResponse([], 'Invalid M3U URL.');
                }
                $m3uContent = $response->body();
            } else if (file_exists($url)) {
                $m3uContent = file_get_contents($url);
            } else {
                return $this->getErrorJsonResponse([], 'Invalid M3U URL or local file path not found.');
            }
            $m3uSource = $url;
        } else {
            return $this->getErrorJsonResponse([], 'Please provide an M3U URL, local Server File Path, or upload an M3U File.');
        }

        $pattern = '/#EXTINF:(?P<attributes>[^\n]+),(?P<name>[^\n]+)\n(?P<url>http[^\n]+)/';

        if (!preg_match_all($pattern, $m3uContent, $matches, PREG_SET_ORDER)) {
            return $this->getErrorJsonResponse([], 'No channels found in M3U URL.');
        }

        DB::disableQueryLog();
        DB::beginTransaction();

        try {
            // Load existing parent categories
            $existingCategories = SeriesCategory::whereNull('categorie_id')
                ->whereNull('sub_category_id')
                ->whereNotNull('series_categorie_name')
                ->pluck('id', 'series_categorie_name')
                ->toArray();

            $existingShows = DB::table('tv_shows')->pluck('id', 'title')->toArray();
            $existingSeasons = [];

            $existingDbSeasons = DB::table('tv_show_seasons')->get(['id', 'tv_show_id', 'season_number']);
            foreach ($existingDbSeasons as $s) {
                $existingSeasons[$s->tv_show_id][$s->season_number] = $s->id;
            }

            $organizations = $request->input('organization', []);
            if (!is_array($organizations)) {
                $organizations = [$organizations];
            }

            if (empty(array_filter($organizations))) {
                $organizations = Organization::pluck('id')->toArray();
            }

            $authId = Auth::id() ?? 1;
            $now = now();

            $m3uInserts = [];
            $orgInserts = [];
            $tvCatOrgInserts = [];
            $episodeInserts = [];

            foreach ($matches as $match) {

                $rawName = trim($match['name']);
                $url = trim($match['url']);
                $attributes = $match['attributes'];

                preg_match('/tvg-id="([^"]*)"/', $attributes, $tvgIdMatch);
                $epgId = $tvgIdMatch[1] ?? null;

                preg_match('/tvg-logo="([^"]*)"/', $attributes, $tvgLogoMatch);
                $posterImage = $tvgLogoMatch[1] ?? null;

                preg_match('/\((\d+p)\)/', $rawName, $qualityMatch);
                $resolution = $qualityMatch[1] ?? null;

                $channelName = preg_replace('/\s*\(\d+p\)/', '', $rawName);

                $geoPolicy = 0;
                if (stripos($channelName, '[Geo-blocked]') !== false) {
                    $geoPolicy = 1;
                    $channelName = trim(str_ireplace('[Geo-blocked]', '', $channelName));
                }

                // Check Sintel - S01E01 pattern first using powerful extraction regex
                $sePattern = '/^(.*?)\s*[-:]?\s*S(\d+)[E|e](\d+)\s*(?:[-:]\s*(.*))?/i';
                if (!preg_match($sePattern, $channelName, $seMatches)) {
                    // Try alternatives or fallback to assumed formats
                    $showName = $channelName;
                    $seasonNumber = 1;
                    $episodeNumber = 1;
                    $episodeName = "Episode 1";
                } else {
                    $showName = trim($seMatches[1], " -:");
                    $seasonNumber = (int) $seMatches[2];
                    $episodeNumber = (int) $seMatches[3];
                    $episodeName = (isset($seMatches[4]) && trim($seMatches[4]) != '') ? trim($seMatches[4]) : ("Episode " . $episodeNumber);
                }

                // Extract group-title
                preg_match('/group-title="([^"]*)"/', $attributes, $groupTitleMatch);
                $groupTitle = $groupTitleMatch[1] ?? null;

                $assignedCategories = [];

                if ($groupTitle) {
                    $groupTitles = array_filter(array_map('trim', explode(';', $groupTitle)));

                    foreach ($groupTitles as $catName) {
                        if (empty($catName)) {
                            continue;
                        }

                        // Check if Parent Category exists, else create
                        if (!isset($existingCategories[$catName])) {
                            $category = SeriesCategory::create([
                                'series_categorie_name' => $catName
                            ]);
                            $existingCategories[$catName] = $category->id;

                            foreach ($organizations as $orgId) {
                                if ($orgId) {
                                    $tvCatOrgInserts[] = [
                                        'series_category_id' => $category->id,
                                        'organization_id' => $orgId,
                                        'created_by' => $authId,
                                        'created_at' => $now,
                                        'updated_at' => $now,
                                    ];
                                }
                            }
                        }

                        $assignedCategories[] = $catName;
                    }
                }

                if (count($tvCatOrgInserts) >= 500) {
                    DB::table('series_category_organizations')->insert($tvCatOrgInserts);
                    $tvCatOrgInserts = [];
                }

                // Identify or Create Show
                if (!isset($existingShows[$showName])) {
                    $showId = DB::table('tv_shows')->insertGetId([
                        'title' => $showName,
                        'poster_image' => $posterImage,
                        'geo_policy' => $geoPolicy,
                        'category' => !empty($assignedCategories) ? json_encode(array_values(array_unique($assignedCategories))) : null,
                        'scheduled_publishing' => 1,
                        'publish_now' => 1,
                        'is_active' => 1,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                    $existingShows[$showName] = $showId;

                    $m3uInserts[] = [
                        'tv_show_id' => $showId,
                        'm3u_url' => $m3uSource,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];

                    foreach ($organizations as $orgId) {
                        if ($orgId) {
                            $orgInserts[] = [
                                'tv_show_id' => $showId,
                                'organization_id' => $orgId,
                                'created_by' => $authId,
                                'created_at' => $now,
                                'updated_at' => $now,
                            ];
                        }
                    }
                } else {
                    $showId = $existingShows[$showName];
                }

                // Identify or Create Season
                if (!isset($existingSeasons[$showId][$seasonNumber])) {
                    $seasonId = DB::table('tv_show_seasons')->insertGetId([
                        'tv_show_id' => $showId,
                        'title' => "Season",
                        'season_number' => $seasonNumber,
                        'poster_image' => $posterImage,
                        'scheduled_publishing' => 1,
                        'publish_now' => 1,
                        'is_active' => 1,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                    $existingSeasons[$showId][$seasonNumber] = $seasonId;
                } else {
                    $seasonId = $existingSeasons[$showId][$seasonNumber];
                }

                // Append Episode Info to Batch Arrays
                $episodeInserts[] = [
                    'tv_show_id' => $showId,
                    'season_id' => $seasonId,
                    'episode_name' => $episodeName,
                    'episode_number' => $episodeNumber,
                    'streaming_url' => $url,
                    'poster_image' => $posterImage,
                    'resolution' => $resolution ?? null,
                    'scheduled_publishing' => 1,
                    'publish_now' => 1,
                    'is_active' => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];

                // Insert into db every 500 loops to avoid maxing server memory array limit
                if (count($m3uInserts) >= 500) {
                    DB::table('m3u_tvshow')->insert($m3uInserts);
                    $m3uInserts = [];
                }

                if (count($orgInserts) >= 500) {
                    DB::table('tv_show_organization')->insert($orgInserts);
                    $orgInserts = [];
                }

                if (count($episodeInserts) >= 500) {
                    DB::table('tvshow_season_episodes')->insert($episodeInserts);
                    $episodeInserts = [];
                }
            }

            // Flush remaining batches
            if (!empty($m3uInserts)) {
                DB::table('m3u_tvshow')->insert($m3uInserts);
            }
            if (!empty($orgInserts)) {
                DB::table('tv_show_organization')->insert($orgInserts);
            }
            if (!empty($tvCatOrgInserts)) {
                DB::table('series_category_organizations')->insert($tvCatOrgInserts);
            }
            if (!empty($episodeInserts)) {
                DB::table('tvshow_season_episodes')->insert($episodeInserts);
            }

            DB::commit();
            return 'success';

        } catch (\Exception $e) {
            DB::rollBack();
            return 'Error uploading M3U file elements: ' . $e->getMessage();
        }
    }
}

