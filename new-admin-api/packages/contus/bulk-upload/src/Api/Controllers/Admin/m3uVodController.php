<?php

namespace Contus\BulkUpload\Api\Controllers\Admin;

use Contus\BulkUpload\Repositories\m3uVodRepository;
use Contus\Organizations\Model\Organization;
use Contus\Video\Models\VodCategory;
use Illuminate\Http\Request;
use Contus\Base\ApiController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class m3uVodController extends ApiController
{

    public function __construct(m3uVodRepository $m3uVodRepository)
    {
        parent::__construct();
        $this->repository = $m3uVodRepository;
        $this->repository->setRequestType(static::REQUEST_TYPE);
    }

    public function getInfo()
    {
        return $this->successResponse('M3U Vod Info', []);
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
            $existingCategories = VodCategory::whereNull('categorie_id')
                ->whereNull('sub_category_id')
                ->whereNotNull('vod_categorie_name')
                ->pluck('id', 'vod_categorie_name')
                ->toArray();

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

            foreach ($matches as $match) {

                $rawName = trim($match['name']);
                $url = trim($match['url']);
                $attributes = $match['attributes'];

                preg_match('/tvg-id="([^"]*)"/', $attributes, $tvgIdMatch);
                $epgId = $tvgIdMatch[1] ?? null;

                preg_match('/tvg-logo="([^"]*)"/', $attributes, $tvgLogoMatch);
                $posterImage = $tvgLogoMatch[1] ?? null;

                preg_match('/tvg-chno="([^"]*)"/', $attributes, $tvgChnoMatch);
                $channelNumber = $tvgChnoMatch[1] ?? null;

                preg_match('/\((\d+p)\)/', $rawName, $qualityMatch);
                $resolution = $qualityMatch[1] ?? null;

                $channelName = preg_replace('/\s*\(\d+p\)/', '', $rawName);

                $geoPolicy = 0;
                if (stripos($channelName, '[Geo-blocked]') !== false) {
                    $geoPolicy = 1;
                    $channelName = trim(str_ireplace('[Geo-blocked]', '', $channelName));
                }

                $videoQuality = null;

                if ($resolution) {
                    if (in_array($resolution, ['270p', '360p'])) {
                        $videoQuality = 'SD';
                    } elseif (in_array($resolution, ['480p', '576p'])) {
                        $videoQuality = 'HD';
                    } elseif ($resolution == '720p') {
                        $videoQuality = 'FHD';
                    } elseif ($resolution == '1080p') {
                        $videoQuality = 'UHD';
                    }
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
                            $category = VodCategory::create([
                                'vod_categorie_name' => $catName
                            ]);
                            $existingCategories[$catName] = $category->id;

                            foreach ($organizations as $orgId) {
                                if ($orgId) {
                                    $tvCatOrgInserts[] = [
                                        'vod_category_id' => $category->id,
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
                    DB::table('vod_category_organizations')->insert($tvCatOrgInserts);
                    $tvCatOrgInserts = [];
                }

                // Batch DB Inserts block to skip eloquent overhead.
                $channelId = DB::table('video_on_demand')->insertGetId([
                    'title' => trim($channelName),
                    'streaming_url' => $url,
                    'poster_image' => $posterImage,
                    'video_quality' => $videoQuality,
                    'geo_policy' => $geoPolicy,
                    'category' => !empty($assignedCategories) ? json_encode(array_values(array_unique($assignedCategories))) : null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                $m3uInserts[] = [
                    'vod_id' => $channelId,
                    'm3u_url' => $m3uSource,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];

                foreach ($organizations as $orgId) {
                    if ($orgId) {
                        $orgInserts[] = [
                            'vod_id' => $channelId,
                            'organization_id' => $orgId,
                            'created_by' => $authId,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ];
                    }
                }

                // Insert into db every 500 loops to avoid maxing server memory array limit
                if (count($m3uInserts) >= 500) {
                    DB::table('m3u_vod')->insert($m3uInserts);
                    $m3uInserts = [];
                }

                if (count($orgInserts) >= 500) {
                    DB::table('vod_organization')->insert($orgInserts);
                    $orgInserts = [];
                }
            }

            // Flush remaining batches
            if (!empty($m3uInserts)) {
                DB::table('m3u_vod')->insert($m3uInserts);
            }
            if (!empty($orgInserts)) {
                DB::table('vod_organization')->insert($orgInserts);
            }
            if (!empty($tvCatOrgInserts)) {
                DB::table('vod_category_organizations')->insert($tvCatOrgInserts);
            }

            DB::commit();
            return 'success';

        } catch (\Exception $e) {
            DB::rollBack();
            return 'Error uploading M3U file elements: ' . $e->getMessage();
        }
    }

}