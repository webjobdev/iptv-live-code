<?php

namespace Contus\BulkUpload\Api\Controllers\Admin;

use Contus\BulkUpload\Repositories\m3uChannelRepository;
use Contus\Organizations\Model\Organization;
use Contus\Video\Models\TvCategory;
use Illuminate\Http\Request;
use Contus\Base\ApiController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class m3uChannelController extends ApiController
{

    public function __construct(m3uChannelRepository $m3uChannelRepository)
    {
        parent::__construct();
        $this->repository = $m3uChannelRepository;
        $this->repository->setRequestType(static::REQUEST_TYPE);
    }

    public function getInfo()
    {
        return $this->successResponse('M3U Channel Info', []);
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
            $existingCategories = TvCategory::whereNull('categorie_id')
                ->whereNotNull('tv_categorie_name')
                ->pluck('id', 'tv_categorie_name')
                ->toArray();

            // Load existing child categories
            $existingChildCategories = [];
            $dbChildCategories = TvCategory::whereNotNull('categorie_id')
                ->whereNotNull('category_name')
                ->get(['id', 'categorie_id', 'category_name']);
            foreach ($dbChildCategories as $cc) {
                $existingChildCategories[$cc->categorie_id . '_' . $cc->category_name] = $cc->id;
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

                $assignedSubCategories = [];

                if ($groupTitle) {
                    $groupTitles = array_filter(array_map('trim', explode(';', $groupTitle)));

                    if (!empty($groupTitles)) {
                        $parentName = array_shift($groupTitles);

                        // Generate Parent Category
                        if (!isset($existingCategories[$parentName])) {
                            $category = TvCategory::create([
                                'tv_categorie_name' => $parentName
                            ]);
                            $existingCategories[$parentName] = $category->id;

                            foreach ($organizations as $orgId) {
                                if ($orgId) {
                                    $tvCatOrgInserts[] = [
                                        'tv_category_id' => $category->id,
                                        'organization_id' => $orgId,
                                        'created_by' => $authId,
                                        'created_at' => $now,
                                        'updated_at' => $now,
                                    ];
                                }
                            }
                        }
                        $parentId = $existingCategories[$parentName];

                        // Generate Child Categories (e.g. Kids, Religious)
                        foreach ($groupTitles as $childName) {
                            $childKey = $parentId . '_' . $childName;
                            if (!isset($existingChildCategories[$childKey])) {
                                $childCategory = TvCategory::create([
                                    'category_name' => $childName,
                                    'categorie_id' => $parentId,
                                    'category_order' => 1
                                ]);
                                $existingChildCategories[$childKey] = $childCategory->id;
                            }
                            $assignedSubCategories[] = $existingChildCategories[$childKey];
                        }
                    }
                }

                if (count($tvCatOrgInserts) >= 500) {
                    DB::table('tv_category_organizations')->insert($tvCatOrgInserts);
                    $tvCatOrgInserts = [];
                }

                // Batch DB Inserts block to skip eloquent overhead.
                $channelId = DB::table('channels')->insertGetId([
                    'channel_name' => trim($channelName),
                    'streaming_url' => $url,
                    'epg_id' => $epgId,
                    'poster_image' => $posterImage,
                    'video_quality' => $videoQuality,
                    'sorting_number' => $channelNumber ?? '',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                $m3uInserts[] = [
                    'channel_id' => $channelId,
                    'm3u_url' => $m3uSource,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];

                foreach ($organizations as $orgId) {
                    if ($orgId) {
                        $orgInserts[] = [
                            'channel_id' => $channelId,
                            'organization_id' => $orgId,
                            'created_by' => $authId,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ];
                    }
                }

                // Insert Channel mapping to Subcategories
                foreach ($assignedSubCategories as $subCatId) {
                    $tvCatMapId = DB::table('tv_category')->insertGetId([
                        'channel_id' => $channelId,
                        'sub_category_id' => $subCatId,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }

                // Insert into db every 500 loops to avoid maxing server memory array limit
                if (count($m3uInserts) >= 500) {
                    DB::table('m3u_channel')->insert($m3uInserts);
                    $m3uInserts = [];
                }

                if (count($orgInserts) >= 500) {
                    DB::table('channel_organization')->insert($orgInserts);
                    $orgInserts = [];
                }

                if (count($tvCatOrgInserts) >= 500) {
                    DB::table('tv_category_organizations')->insert($tvCatOrgInserts);
                    $tvCatOrgInserts = [];
                }
            }

            // Flush remaining batches
            if (!empty($m3uInserts)) {
                DB::table('m3u_channel')->insert($m3uInserts);
            }
            if (!empty($orgInserts)) {
                DB::table('channel_organization')->insert($orgInserts);
            }
            if (!empty($tvCatOrgInserts)) {
                DB::table('tv_category_organizations')->insert($tvCatOrgInserts);
            }

            DB::commit();
            return 'success';

        } catch (\Exception $e) {
            DB::rollBack();
            return 'Error uploading M3U file elements: ' . $e->getMessage();
        }
    }

}