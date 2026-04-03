<?php

namespace Contus\XtreamCode\Api\Controllers;

use App\Http\Controllers\Controller;
use Contus\AppApi\Api\Controllers\AppApiController;
use Contus\Base\ApiController;
use Contus\Channel\Model\Channel;
use Contus\ChannelServices\Model\EpgProgram;
use Contus\Organizations\Model\ChannelContet;
use Contus\Organizations\Model\LiveEventContent;
use Contus\Organizations\Model\OrganizationDetail;
use Contus\Organizations\Model\OrgSubscribers;
use Contus\Organizations\Model\TvShowContent;
use Contus\Organizations\Model\VodContent;
use Contus\Tvshow\Model\TvShow;
use Contus\Video\Models\SeriesCategory;
use Contus\Video\Models\VodCategory;
use Contus\Vod\Model\VideoOnDemad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Contus\Video\Models\SeriesCategoryOrganizations;
use Contus\Video\Models\TvCategory;
use Contus\Video\Models\TvCategoryOrganizations;
use Contus\Video\Models\VodCategoryOrganizations;


class XtreamController extends ApiController
{

    public static function getAssignedSets($orgId, $category)
    {
        if ($category == 'movies') {
            $assignedMovies = VodContent::where('organization_id', $orgId)
                ->pluck('assigned_vod')
                ->toArray();

            $res = collect($assignedMovies)
                ->flatMap(function ($item) {
                    $decoded = is_string($item) ? json_decode($item, true) : $item;
                    return collect($decoded)->pluck('id');
                })
                ->unique()
                ->values()
                ->toArray();
        } else if ($category == 'tv_shows') {
            $assignedTvShows = TvShowContent::where('organization_id', $orgId)
                ->pluck('assigned_tv_show')
                ->toArray();

            $res = collect($assignedTvShows)
                ->flatMap(function ($item) {
                    $decoded = is_string($item) ? json_decode($item, true) : $item;
                    return collect($decoded)->pluck('id');
                })
                ->unique()
                ->values()
                ->toArray();
        } else if ($category == 'channel') {
            $assignedChannels = ChannelContet::where('organization_id', $orgId)
                ->pluck('assigned_channels')
                ->toArray();

            $res = collect($assignedChannels)
                ->flatMap(function ($item) {
                    $decoded = is_string($item) ? json_decode($item, true) : $item;
                    return collect($decoded)->pluck('id');
                })
                ->unique()
                ->values()
                ->toArray();
        } else if ($category == 'live-events') {
            $assignedLiveEvent = LiveEventContent::where('organization_id', $orgId)
                ->pluck('assigned_channels')
                ->toArray();

            $res = collect($assignedLiveEvent)
                ->flatMap(function ($item) {
                    $decoded = is_string($item) ? json_decode($item, true) : $item;
                    return collect($decoded)->pluck('id');
                })
                ->unique()
                ->values()
                ->toArray();
        } else {
            $res = [];
        }

        return $res;
    }


    /**
     * Handle XTREAM Player API Main Entry Point
     * /api/xtream/player_api.php
     */


    // public function handle(Request $request)
    // {
    //     // dd(99);
    //     // 1. Validate Initial Request
    //     $validation = Validator::make($request->all(), [
    //         'username' => 'required|string',
    //         'password' => 'required|string',
    //         'action' => 'nullable|string',
    //     ]);

    //     Log::info('Xtream API Request', [
    //         'username' => $request->username,
    //         'action' => $request->action,
    //         'ip' => $request->ip(),
    //         'user_agent' => $request->userAgent()
    //     ]);

    //     if ($validation->fails()) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Validation failed',
    //             'errors' => $validation->errors()
    //         ], 422);
    //     }

    //     // 2. Validate Credentials & License
    //     $user = OrgSubscribers::with('subscription_payment_detail')
    //         ->where('email', $request->username)
    //         ->first();

    //     Log::info('Xtream API User Lookup', [
    //         'username' => $request->username,
    //         'user_found' => $user ? true : false
    //     ]);


    //     if (!$user || !password_verify($request->password, $user->password)) {
    //         return response()->json([
    //             'user_info' => ['auth' => 0],
    //             'status' => false,
    //             'message' => 'Invalid credentials'
    //         ], 401);
    //     }

    //     $org = OrganizationDetail::find($user->organization_id);
    //     if (!$org) {
    //         return response()->json([
    //             'user_info' => ['auth' => 0],
    //             'status' => false,
    //             'message' => "Organization not found"
    //         ], 403);
    //     }

    //     Log::info('Xtream API Organization Lookup', [
    //         'organization_id' => $org,
    //         'organization_found' => true
    //     ]);

    //     $orgId = $org->id;

    //     Log::info('Xtream API Subscription Check', [
    //         'user_id' => $user->id,
    //         'subscription_details' => $user->subscription_payment_detail->toArray()
    //     ]);

    //     // check subscription validity, expiration, etc. based on OrgSubscriberAndPayment
    //     $subscriberPayment = $user->subscription_payment_detail->where('end_date', '>', now())->where('is_active', 1)->first();

    //     Log::info('Xtream API Subscription Validity', [
    //         'user_id' => $user->id,
    //         'subscription_valid' => $subscriberPayment ? true : false,
    //         'subscription_end_date' => $subscriberPayment ? $subscriberPayment->end_date : null
    //     ]);

    //     if (!$subscriberPayment) {
    //         return response()->json([
    //             'user_info' => ['auth' => 0],
    //             'status' => false,
    //             'message' => "Subscription is expired! Please renew your subscription to continue watching."
    //         ], 403);
    //     }


    //     // 3. Action Dispatcher
    //     // Xtream codes uses 'action' parameter to decide what data to return.
    //     // If action is missing or 'login', return User Info + Server Info.

    //     if ($request->has('action')) {

    //         Log::info('Xtream API Action Triggered', [
    //             'action' => $request->action,
    //             'org_id' => $orgId,
    //             'username' => $request->username ?? null,
    //             'ip' => $request->ip()
    //         ]);

    //         switch ($request->action) {
    //             /* ==========================================================================
    //                LIVE TV ACTIONS
    //                ========================================================================== */
    //             case 'get_live_categories':

    //                 Log::info('Fetching Live Categories', [
    //                     'org_id' => $orgId
    //                 ]);

    //                 return response()->json([
    //                     [
    //                         'category_id' => "1",
    //                         'category_name' => "Live TV",
    //                         'parent_id' => 0
    //                     ]
    //                 ]);
    //                 break;

    //             case 'get_live_streams':
    //                 $assignedChannelContents = self::getAssignedSets($orgId, 'channel');

    //                 Log::info('Assigned Live Channel IDs', [
    //                     'org_id' => $orgId,
    //                     'channel_ids' => $assignedChannelContents
    //                 ]);

    //                 $channels = Channel::whereIn('id', $assignedChannelContents)
    //                     ->where('is_active', 1)->get();

    //                 Log::info('Live Channels Fetched', [
    //                     'count' => $channels->count()
    //                 ]);

    //                 $data = $channels->map(function ($channel) {
    //                     return [
    //                         'num' => $channel->id,
    //                         'name' => $channel->channel_name ?? 'Unknown Channel',
    //                         'stream_type' => 'live',
    //                         'stream_id' => $channel->id,
    //                         'stream_icon' => $channel->poster_image ?? '',
    //                         'epg_channel_id' => $channel->epg_channel_id ?? null,
    //                         'added' => (string) strtotime($channel->created_at),
    //                         'category_id' => (string) ($channel->category_id ?? "1"),
    //                         'custom_sid' => "",
    //                         'tv_archive' => 0,
    //                         'direct_source' => "",
    //                         'tv_archive_duration' => 0,
    //                     ];
    //                 });
    //                 return response()->json($data->values()->toArray());
    //                 break;

    //             /* ==========================================================================
    //                VOD (MOVIES) ACTIONS
    //                ========================================================================== */
    //             case 'get_vod_categories':
    //                 $categories = VodCategory::where('organization', $orgId)->get();

    //                 Log::info('Fetching VOD Categories', [
    //                     'org_id' => $orgId,
    //                     'count' => $categories->count()
    //                 ]);

    //                 if ($categories->isEmpty()) {
    //                     return response()->json([
    //                         [
    //                             'category_id' => "1",
    //                             'category_name' => "Movies",
    //                             'parent_id' => 0
    //                         ]
    //                     ]);
    //                 }
    //                 $data = $categories->map(function ($cat) {
    //                     return [
    //                         'category_id' => (string) $cat->id,
    //                         'category_name' => $cat->vod_categorie_name ?? 'Movies',
    //                         'parent_id' => 0
    //                     ];
    //                 });
    //                 return response()->json($data->values()->toArray());
    //                 break;

    //             case 'get_vod_streams':
    //                 $assignedVodContents = self::getAssignedSets($orgId, 'movies');

    //                 Log::info('Assigned Movie IDs', [
    //                     'org_id' => $orgId,
    //                     'movie_ids' => $assignedVodContents
    //                 ]);

    //                 $movies = VideoOnDemad::whereIn('id', $assignedVodContents)
    //                     ->where('is_active', 1)->get();

    //                 Log::info('Movies Fetched', [
    //                     'count' => $movies->count()
    //                 ]);

    //                 $data = $movies->map(function ($movie) {
    //                     return [
    //                         'num' => $movie->id,
    //                         'name' => $movie->title ?? 'Unknown Movie',
    //                         'title' => $movie->title ?? 'Unknown Movie',
    //                         'stream_type' => 'movie',
    //                         'stream_id' => $movie->id,
    //                         'stream_icon' => $movie->poster_image ?? '',
    //                         'rating' => $movie->age_rating ?? "5",
    //                         'rating_5based' => 5,
    //                         'added' => (string) strtotime($movie->created_at),
    //                         'category_id' => (string) ($movie->category_id ?? "1"),
    //                         'container_extension' => "mp4",
    //                         'custom_sid' => "",
    //                         'direct_source' => "",
    //                     ];
    //                 });
    //                 return response()->json($data->values()->toArray());
    //                 break;

    //             /* ==========================================================================
    //            SERIES ACTIONS
    //            ========================================================================== */
    //             case 'get_series_categories':
    //                 $categories = SeriesCategory::where('organization', $orgId)->get();

    //                 Log::info('Fetching Series Categories', [
    //                     'org_id' => $orgId,
    //                     'count' => $categories->count()
    //                 ]);

    //                 if ($categories->isEmpty()) {
    //                     return response()->json([
    //                         [
    //                             'category_id' => "1",
    //                             'category_name' => "TV Shows",
    //                             'parent_id' => 0
    //                         ]
    //                     ]);
    //                 }
    //                 $data = $categories->map(function ($cat) {
    //                     return [
    //                         'category_id' => (string) $cat->id,
    //                         'category_name' => $cat->title ?? 'TV Shows',
    //                         'parent_id' => 0
    //                     ];
    //                 });
    //                 return response()->json($data->values()->toArray());
    //                 break;

    //             case 'get_series':
    //                 $assignedTvShowContents = self::getAssignedSets($orgId, 'tv_shows');

    //                 Log::info('Assigned TV Show IDs', [
    //                     'org_id' => $orgId,
    //                     'tv_show_ids' => $assignedTvShowContents
    //                 ]);

    //                 $series = TvShow::whereIn('id', $assignedTvShowContents)
    //                     ->where('is_active', 1)->get();

    //                 Log::info('TV Shows Fetched', [
    //                     'count' => $series->count()
    //                 ]);

    //                 $data = $series->map(function ($show) {
    //                     return [
    //                         'num' => $show->id,
    //                         'name' => $show->title ?? 'Unknown Show',
    //                         'title' => $show->title ?? 'Unknown Show',
    //                         'series_id' => $show->id,
    //                         'cover' => $show->poster_image ?? '',
    //                         'plot' => $show->description ?? '',
    //                         'cast' => $show->presenter ?? '',
    //                         'director' => $show->directors ?? '',
    //                         'genre' => $show->genre ?? "",
    //                         'releaseDate' => (string) $show->release_year,
    //                         'last_modified' => (string) strtotime($show->updated_at),
    //                         'rating' => $show->age_rating ?? "5",
    //                         'rating_5based' => 5,
    //                         'backdrop_path' => [$show->thumbnail_image ?? ''],
    //                         'youtube_trailer' => $show->trailer_url ?? '',
    //                         'episode_run_time' => "0",
    //                         'category_id' => (string) ($show->category_id ?? "1")
    //                     ];
    //                 });
    //                 return response()->json($data->values()->toArray());
    //                 break;

    //             case 'get_series_info':

    //                 Log::warning('Series Info Requested - Not Implemented', [
    //                     'series_id' => $request->series_id ?? null,
    //                     'org_id' => $orgId
    //                 ]);

    //                 // Need to fetch Episodes for a specific series
    //                 // Xtream expects detailed object with 'episodes' array.
    //                 // $seriesId = $request->series_id;
    //                 // Implementation for series info would go here.
    //                 return response()->json([]); // Placeholder
    //                 break;

    //             /* ==========================================================================
    //                EPG ACTIONS (Short)
    //                ========================================================================== */
    //             case 'get_short_epg':

    //                 Log::info('Short EPG Requested', [
    //                     'stream_id' => $request->stream_id ?? null,
    //                     'limit' => $request->limit ?? null
    //                 ]);

    //                 // stream_id=X&limit=X
    //                 return response()->json([
    //                     'epg_listings' => [] // Placeholder
    //                 ]);
    //                 break;
    //         }
    //     }

    //     Log::info('Preparing Xtream Login Response', [
    //         'username' => $user->user_name,
    //         'org_id' => $orgId
    //     ]);

    //     // get categories of live tv, vod, series
    //     $tvCategoryIds = TvCategoryOrganizations::pluck('id');
    //     // $tvCategories = TvCategory::select('category_id', 'tv_categorie_name')->whereIn('id', $tvCategoryIds);
    //     $tvCategories = TvCategory::get();

    //     // get categories of live tv, vod, series
    //     $vodCategoryIds = VodCategoryOrganizations::pluck('id');
    //     // $vodCategories = VodCategory::select('category_id', 'vod_categorie_name')->whereIn('id', $vodCategoryIds);
    //     $vodCategories = VodCategory::get();

    //     // get categories of live tv, vod, series
    //     $seriesCategoryIds = SeriesCategoryOrganizations::pluck('id');
    //     // $seriesCategories = SeriesCategory::select('category_id', 'series_categorie_name')->whereIn('id', $gets);
    //     $seriesCategories = SeriesCategory::get();

    //     // available channels
    //     $assignedChannels = ChannelContet::where('organization_id', $orgId)
    //         ->pluck('assigned_channels')
    //         ->toArray();

    //     $availableChannelIds = [];

    //     foreach ($assignedChannels as $channelJson) {

    //         $decoded = json_decode($channelJson, true);

    //         if (is_array($decoded)) {
    //             foreach ($decoded as $channel) {
    //                 if (isset($channel['id'])) {
    //                     $availableChannelIds[] = $channel['id'];
    //                 }
    //             }
    //         }
    //     }

    //     Log::info('Available Channel IDs After Decoding', [
    //         'org_id' => $orgId,
    //         'channel_ids' => $availableChannelIds
    //     ]);

    //     $channels = Channel::whereIn('id', $availableChannelIds)->get();

    //     Log::info('Final Login Response Ready', [
    //         'tv_categories_count' => $tvCategories->count(),
    //         'vod_categories_count' => $vodCategories->count(),
    //         'series_categories_count' => $seriesCategories->count(),
    //         'available_channels_count' => $channels->count()
    //     ]);

    //     // 4. Default Login Response (UserInfo + ServerInfo)
    //     $serverUrl = $request->getScheme() . '://' . $request->getHost(); // e.g. http://domain.com
    //     if ($request->getPort() != 80 && $request->getPort() != 443) {
    //         $serverUrl .= ':' . $request->getPort();
    //     }

    //     return response()->json([
    //         'user_info' => [
    //             'username' => $user->user_name,
    //             'password' => $request->password,
    //             'message' => "Logged In",
    //             'auth' => 1,
    //             'status' => 'Active',
    //             'exp_date' => (string) strtotime($subscriberPayment->end_date),
    //             'is_trial' => '0',
    //             'active_cons' => '0',
    //             'created_at' => (string) strtotime($user->created_at),
    //             'max_connections' => '10', // Allowed connections
    //             'allowed_output_formats' => ['m3u8', 'ts', 'mp4']
    //         ],
    //         'server_info' => [
    //             'url' => $request->getHost(),
    //             'port' => (string) $request->getPort(),
    //             'https_port' => "443",
    //             'server_protocol' => $request->getScheme(),
    //             'rtmp_port' => "8880",
    //             'timezone' => "UTC", // Config::get('app.timezone')
    //             'timestamp_now' => time(),
    //             'time_now' => date("Y-m-d H:i:s")
    //         ],
    //         'categories' => [
    //             'tv' => $tvCategories,
    //             'vod' => $vodCategories,
    //             'series' => $seriesCategories
    //         ],
    //         'available_channels' => $channels
    //     ]);
    // }








    public function handle(Request $request)
    {
        // 1. Validate Initial Request
        $validation = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string',
            'action' => 'nullable|string',
        ]);

        if ($validation->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validation->errors()
            ], 422);
        }

        // 2. Validate Credentials & License
        $user = OrgSubscribers::with('subscription_payment_detail')
            ->where('email', $request->username)
            ->first();

        if (!$user || !password_verify($request->password, $user->password)) {
            return response()->json([
                'user_info' => ['auth' => 0],
                'status' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }

        $org = OrganizationDetail::find($user->organization_id);
        if (!$org) {
            return response()->json([
                'user_info' => ['auth' => 0],
                'status' => false,
                'message' => "Organization not found"
            ], 403);
        }

        $orgId = $org->id;

        // check subscription validity, expiration, etc. based on OrgSubscriberAndPayment
        $subscriberPayment = $user->subscription_payment_detail->where('end_date', '>', now())->where('is_active', 1)->first();

        if (!$subscriberPayment) {
            return response()->json([
                'user_info' => ['auth' => 0],
                'status' => false,
                'message' => "Subscription is expired! Please renew your subscription to continue watching."
            ], 403);
        }


        // 3. Action Dispatcher
        // Xtream codes uses 'action' parameter to decide what data to return.
        // If action is missing or 'login', return User Info + Server Info.


        $tvCategories = TvCategory::whereHas('getOrganization', function ($query) use ($user) {
            $query->where('organization_id', $user->organization_id);
        })
            ->whereNull('sub_category_id')
            ->whereNull('channel_id')
            ->whereNotNull('tv_categorie_name')
            ->get();

        $vodCategories = VodCategory::whereHas('getOrganization', function ($query) use ($user) {
            $query->where('organization_id', $user->organization_id);
        })->get();

        $seriesCategories = SeriesCategory::whereHas('getOrganization', function ($query) use ($user) {
            $query->where('organization_id', $user->organization_id);
        })->get();



        if ($request->has('action')) {

            switch ($request->action) {
                /* ==========================================================================
                   LIVE TV ACTIONS
                   ========================================================================== */
                case 'get_live_categories':

                    if ($tvCategories->isEmpty()) {
                        return response()->json([
                            [
                                'category_id' => "1",
                                'category_name' => "Live TV",
                                'parent_id' => 0
                            ]
                        ]);
                    }

                    $data = $tvCategories->map(function ($cat) {
                        return [
                            'category_id' => (string) $cat->id,
                            'category_name' => $cat->tv_categorie_name ?? 'Live TV',
                            'parent_id' => $cat->categorie_id ?? 0
                        ];
                    });

                    return response()->json($data->values()->toArray());
                    break;

                case 'get_live_streams':
                    $assignedChannelContents = self::getAssignedSets($orgId, 'channel');

                    $channels = Channel::whereIn('id', $assignedChannelContents)
                        ->where('is_active', 1)->get();

                    // Leaf rows: channel_id = channel.id, sub_category_id = sub-category row id
                    $channelCategoryEntries = TvCategory::whereIn('channel_id', $channels->pluck('id')->toArray())
                        ->whereNotNull('channel_id')
                        ->get();

                    $channelCategoryMap = $channelCategoryEntries->keyBy('channel_id');

                    // Load ALL tv_category rows keyed by id (needed for both sub-cat and top-level lookups)
                    $allTvCategoriesById = TvCategory::all()->keyBy('id');

                    $data = $channels->map(function ($channel) use ($channelCategoryMap, $allTvCategoriesById) {
                        $catEntry = $channelCategoryMap->get($channel->id);

                        $resolvedCategoryId   = null;
                        $resolvedCategoryName = 'Live TV';

                        if ($catEntry) {
                            if ($catEntry->sub_category_id) {
                                $subCat     = $allTvCategoriesById->get((int) $catEntry->sub_category_id);
                                $topLevelId = $subCat ? (int) $subCat->categorie_id : null;
                            } else {
                                // Direct 2-level: leaf->categorie_id = top-level
                                $topLevelId = $catEntry->categorie_id ? (int) $catEntry->categorie_id : null;
                            }

                            if ($topLevelId) {
                                $topCat               = $allTvCategoriesById->get($topLevelId);
                                $resolvedCategoryId   = $topLevelId;
                                $resolvedCategoryName = $topCat
                                    ? ($topCat->tv_categorie_name ?? $topCat->category_name ?? 'Live TV')
                                    : 'Live TV';
                            } elseif ($catEntry->sub_category_id) {
                                // Fallback: use sub-category itself as category
                                $subCat               = $allTvCategoriesById->get((int) $catEntry->sub_category_id);
                                $resolvedCategoryId   = (int) $catEntry->sub_category_id;
                                $resolvedCategoryName = $subCat
                                    ? ($subCat->tv_categorie_name ?? $subCat->category_name ?? 'Live TV')
                                    : 'Live TV';
                            }
                        }

                        return [
                            'num'                => (int) $channel->id,
                            'name'               => $channel->channel_name ?? 'Unknown Channel',
                            'title'              => $channel->channel_name ?? 'Unknown Channel',
                            'year'               => null,
                            'stream_type'        => 'live',
                            'type_name'          => 'Live Streams',
                            'stream_id'          => (int) $channel->id,
                            'stream_icon'        => $channel->poster_image ?? '',
                            'epg_channel_id'     => $channel->epg_channel_id ?? null,
                            'added'              => (string) strtotime($channel->created_at),
                            'category_name'      => $resolvedCategoryName,
                            'category_id'        => (string) ($resolvedCategoryId ?? "1"),
                            'series_no'          => null,
                            'live'               => "1",
                            'container_extension'=> 'm3u8',
                            'custom_sid'         => "",
                            'tv_archive'         => 0,
                            'direct_source'      => $channel->streaming_url ?? "",
                            'tv_archive_duration'=> 0,
                        ];
                    });
                    return response()->json($data->values()->toArray());
                    break;

                /* ==========================================================================
                   VOD (MOVIES) ACTIONS
                   ========================================================================== */
                case 'get_vod_categories':

                    if ($vodCategories->isEmpty()) {
                        return response()->json([
                            [
                                'category_id' => "1",
                                'category_name' => "Movies",
                                'parent_id' => 0
                            ]
                        ]);
                    }
                    $data = $vodCategories->map(function ($cat) {
                        return [
                            'category_id' => (string) $cat->id,
                            'category_name' => $cat->vod_categorie_name ?? 'Movies',
                            'parent_id' => $cat->categorie_id ?? 0
                        ];
                    });
                    return response()->json($data->values()->toArray());
                    break;

                case 'get_vod_streams':
                    $assignedVodContents = self::getAssignedSets($orgId, 'movies');

                    $movies = VideoOnDemad::whereIn('id', $assignedVodContents)
                        ->where('is_active', 1)->get();

                    $data = $movies->map(function ($movie) use ($vodCategories) {
                        $catArray = is_string($movie->category) ? json_decode($movie->category, true) : $movie->category;
                        $catName = (is_array($catArray) && count($catArray) > 0) ? $catArray[0] : (is_string($movie->category) ? $movie->category : 'Movies');

                        $matchedCategory = $vodCategories->first(function ($cat) use ($catName) {
                            return strcasecmp(trim($cat->vod_categorie_name), trim($catName)) === 0;
                        });

                        if (!$matchedCategory) {
                            $fallbackId = \Contus\Video\Models\VodCategory::where('vod_categorie_name', 'like', trim($catName))->value('id');
                        }

                        $categoryId = $matchedCategory ? $matchedCategory->id : ($fallbackId ?? ($movie->category_id ?? "1"));

                        return [
                            'num' => (int) $movie->id,
                            'name' => $movie->title ?? 'Unknown Movie',
                            'title' => $movie->title ?? 'Unknown Movie',
                            'year' => (string) ($movie->release_year ?? ''),
                            'stream_type' => 'movie',
                            'type_name' => 'Movies',
                            'stream_id' => (int) ($movie->id + 100000),
                            'stream_icon' => $movie->poster_image ?? '',
                            'epg_channel_id' => null,
                            'added' => (string) strtotime($movie->created_at),
                            'category_name' => $catName,
                            'category_id' => (string) $categoryId,
                            'series_no' => null,
                            'live' => "0",
                            'container_extension' => "mp4",
                            'custom_sid' => "",
                            'tv_archive' => 0,
                            'direct_source' => $movie->streaming_url,
                            'tv_archive_duration' => 0,
                        ];
                    });
                    return response()->json($data->values()->toArray());
                    break;

                /* ==========================================================================
               SERIES ACTIONS
               ========================================================================== */
                case 'get_series_categories':

                    if ($seriesCategories->isEmpty()) {
                        return response()->json([
                            [
                                'category_id' => "1",
                                'category_name' => "TV Shows",
                                'parent_id' => 0
                            ]
                        ]);
                    }
                    $data = $seriesCategories->map(function ($cat) {
                        return [
                            'category_id' => (string) $cat->id,
                            'category_name' => $cat->series_categorie_name ?? 'TV Shows',
                            'parent_id' => $cat->categorie_id ?? 0
                        ];
                    });
                    return response()->json($data->values()->toArray());
                    break;

                case 'get_series':
                    $assignedTvShowContents = self::getAssignedSets($orgId, 'tv_shows');

                    $series = TvShow::whereIn('id', $assignedTvShowContents)
                        ->where('is_active', 1)->get();

                    $data = $series->map(function ($show) {
                        return [
                            'num' => $show->id,
                            'name' => $show->title ?? 'Unknown Show',
                            'title' => $show->title ?? 'Unknown Show',
                            'series_id' => $show->id,
                            'cover' => $show->poster_image ?? '',
                            'plot' => $show->description ?? '',
                            'cast' => $show->presenter ?? '',
                            'director' => $show->directors ?? '',
                            'genre' => $show->genre ?? "",
                            'releaseDate' => (string) $show->release_year,
                            'last_modified' => (string) strtotime($show->updated_at),
                            'rating' => $show->age_rating ?? "5",
                            'rating_5based' => 5,
                            'backdrop_path' => [$show->thumbnail_image ?? ''],
                            'youtube_trailer' => $show->trailer_url ?? '',
                            'episode_run_time' => "0",
                            'category_id' => (string) ($show->category_id ?? "1")
                        ];
                    });
                    return response()->json($data->values()->toArray());
                    break;

                case 'get_series_info':

                    return response()->json([]); 
                    break;

                /* ==========================================================================
                   EPG ACTIONS (Short)
                   ========================================================================== */
                case 'get_short_epg':

                    // stream_id=X&limit=X
                    return response()->json([
                        'epg_listings' => []
                    ]);
                    break;
            }
        }

        $formattedLiveCategories = $tvCategories->map(function ($cat) {
            return [
                'category_id' => (string) $cat->id,
                'category_name' => $cat->tv_categorie_name ?? 'Live TV',
                'parent_id' => $cat->categorie_id ?? 0
            ];
        })->values()->toArray();

        $formattedVodCategories = $vodCategories->map(function ($cat) {
            return [
                'category_id' => (string) $cat->id,
                'category_name' => $cat->vod_categorie_name ?? 'Movies',
                'parent_id' => $cat->categorie_id ?? 0
            ];
        })->values()->toArray();

        $formattedSeriesCategories = $seriesCategories->map(function ($cat) {
            return [
                'category_id' => (string) $cat->id,
                'category_name' => $cat->series_categorie_name ?? 'TV Shows',
                'parent_id' => $cat->categorie_id ?? 0
            ];
        })->values()->toArray();

        // available channels — use getAssignedSets which handles already-decoded arrays from model casts
        $availableChannelIds = self::getAssignedSets($orgId, 'channel');

        $channels = Channel::whereIn('id', $availableChannelIds)->get();

        // Build a map of channel_id => TvCategory leaf row for login response channels
        $loginChannelCategoryEntries = TvCategory::whereIn('channel_id', $channels->pluck('id')->toArray())
            ->whereNotNull('channel_id')
            ->get();

        $loginChannelCategoryMap = $loginChannelCategoryEntries->keyBy('channel_id');

        // Load ALL tv_category rows keyed by id (needed for sub-cat and top-level lookups)
        $allTvCategoriesById = TvCategory::all()->keyBy('id');

        $formattedChannels = $channels->map(function ($channel) use ($loginChannelCategoryMap, $allTvCategoriesById) {
            $catEntry = $loginChannelCategoryMap->get($channel->id);

            $resolvedCategoryId   = null;
            $resolvedCategoryName = 'Live TV';

            if ($catEntry) {
                if ($catEntry->sub_category_id) {
                    $subCat     = $allTvCategoriesById->get((int) $catEntry->sub_category_id);
                    $topLevelId = $subCat ? (int) $subCat->categorie_id : null;
                } else {
                    // Direct 2-level: leaf->categorie_id = top-level
                    $topLevelId = $catEntry->categorie_id ? (int) $catEntry->categorie_id : null;
                }

                if ($topLevelId) {
                    $topCat               = $allTvCategoriesById->get($topLevelId);
                    $resolvedCategoryId   = $topLevelId;
                    $resolvedCategoryName = $topCat
                        ? ($topCat->tv_categorie_name ?? $topCat->category_name ?? 'Live TV')
                        : 'Live TV';
                } elseif ($catEntry->sub_category_id) {
                    // Fallback: use sub-category itself as category
                    $subCat               = $allTvCategoriesById->get((int) $catEntry->sub_category_id);
                    $resolvedCategoryId   = (int) $catEntry->sub_category_id;
                    $resolvedCategoryName = $subCat
                        ? ($subCat->tv_categorie_name ?? $subCat->category_name ?? 'Live TV')
                        : 'Live TV';
                }
            }

            return [
                'num'                => (int) $channel->id,
                'name'               => $channel->channel_name ?? 'Unknown Channel',
                'title'              => $channel->channel_name ?? 'Unknown Channel',
                'year'               => null,
                'stream_type'        => 'live',
                'type_name'          => 'Live Streams',
                'stream_id'          => (int) $channel->id,
                'stream_icon'        => $channel->poster_image ?? '',
                'epg_channel_id'     => $channel->epg_channel_id ?? null,
                'added'              => (string) strtotime($channel->created_at),
                'category_id'        => (string) ($resolvedCategoryId ?? "1"),
                'category_name'      => $resolvedCategoryName,
                'series_no'          => null,
                'live'               => "1",
                'container_extension'=> 'm3u8',
                'custom_sid'         => "",
                'tv_archive'         => 0,
                'direct_source'      => $channel->streaming_url ?? "",
                'tv_archive_duration'=> 0,
            ];
        })->toArray();

        $assignedVodContents = self::getAssignedSets($orgId, 'movies');

        if (empty($assignedVodContents)) {
            $movies = VideoOnDemad::where('is_active', 1)->get();
        } else {
            $movies = VideoOnDemad::whereIn('id', $assignedVodContents)->where('is_active', 1)->get();
        }

        $formattedMovies = $movies->map(function ($movie) use ($vodCategories) {
            $catArray = is_string($movie->category) ? json_decode($movie->category, true) : $movie->category;
            $catName = (is_array($catArray) && count($catArray) > 0) ? $catArray[0] : (is_string($movie->category) ? $movie->category : 'Movies');

            $matchedCategory = $vodCategories->first(function ($cat) use ($catName) {
                return strcasecmp(trim($cat->vod_categorie_name), trim($catName)) === 0;
            });

            if (!$matchedCategory) {
                $fallbackId = VodCategory::where('vod_categorie_name', 'like', trim($catName))->value('id');
            }

            $categoryId = $matchedCategory ? $matchedCategory->id : ($fallbackId ?? ($movie->category_id ?? "1"));

            // Offset movie stream_id by 100000 to prevent collision with live channel IDs.
            // e.g. movie DB id=2 → stream_id=100002
            // The stream() controller reverses this offset to find the real DB record.
            $streamId = (int) ($movie->id + 100000);

            return [
                'num' => (int) $movie->id,
                'name' => $movie->title ?? 'Unknown Movie',
                'title' => $movie->title ?? 'Unknown Movie',
                'year' => (string) ($movie->release_year ?? ''),
                'stream_type' => 'movie',
                'type_name' => 'Movies',
                'stream_id' => $streamId,
                'stream_icon' => $movie->poster_image ?? '',
                'epg_channel_id' => null,
                'added' => (string) strtotime($movie->created_at),
                'category_name' => $catName,
                'category_id' => (string) $categoryId,
                'series_no' => null,
                'live' => "0",
                'container_extension' => "mp4",
                'custom_sid' => "",
                'tv_archive' => 0,
                'direct_source' => $movie->streaming_url,
                'tv_archive_duration' => 0,
            ];
        })->toArray();

        // Merge channels + movies as an indexed array (IPTV Smarters web player expects an array)
        $allAvailableChannels = array_merge($formattedChannels, $formattedMovies);

        // 4. Default Login Response (UserInfo + ServerInfo)
        $serverUrl = $request->getScheme() . '://' . $request->getHost();
        if ($request->getPort() != 80 && $request->getPort() != 443) {
            $serverUrl .= ':' . $request->getPort();
        }

        return response()->json([
            'user_info' => [
                'username' => $user->email,
                'password' => $request->password,
                'message' => "Logged In",
                'auth' => 1,
                'status' => 'Active',
                'exp_date' => (string) strtotime($subscriberPayment->end_date),
                'is_trial' => '0',
                'active_cons' => '0',
                'created_at' => (string) strtotime($user->created_at),
                'max_connections' => '10', // Allowed connections
                'allowed_output_formats' => ['m3u8', 'ts', 'mp4']
            ],
            'server_info' => [
                'url' => $request->getHost(),
                'port' => (string) $request->getPort(),
                'https_port' => "443",
                'server_protocol' => $request->getScheme(),
                'rtmp_port' => "8880",
                'timezone' => "UTC", // Config::get('app.timezone')
                'timestamp_now' => time(),
                'time_now' => date("Y-m-d H:i:s")
            ],
            'categories' => [
                'live' => $formattedLiveCategories,
                'movie' => $formattedVodCategories,
                'series' => $formattedSeriesCategories
            ],
            'available_channels' => $allAvailableChannels
        ]);
    }






















































    /**
     * XMLTV EPG Generator
     * /api/xtream/xmltv.php
     */
    public function xmltv(Request $request)
    {
        $username = $request->username;
        $password = $request->password;

        $user = OrgSubscribers::where('email', $username)->first();
        if (!$user || !password_verify($password, $user->password)) {
            return response()->json(['message' => 'Authentication Failed'], 401);
        }

        $org = OrganizationDetail::find($user->organization_id);
        if (!$org) {
            return response()->json(['message' => 'Organization Failed'], 403);
        }

        $orgId = $org->id;

        // Fetch assigned channels
        $assignedChannelContents = self::getAssignedSets($orgId, 'channel');

        $channels = Channel::whereIn('id', $assignedChannelContents)
            ->where('is_active', 1)
            ->get();

        // Fetch programs (EPG)
        $channelIds = $channels->pluck('id');

        $programs = EpgProgram::whereIn('channel_id', $channelIds)
            ->orderBy('start_date_time', 'asc')
            ->limit(1000)
            ->get();

        // Build JSON structure
        $response = [
            'generator' => 'IPTV Middleware',
            'channels' => [],
            'programs' => []
        ];

        // Channels
        foreach ($channels as $channel) {
            $response['channels'][] = [
                'id' => $channel->id,
                'name' => $channel->channel_name,
                'poster_image' => $channel->poster_image
            ];
        }

        // Programs
        foreach ($programs as $prog) {
            $response['programs'][] = [
                'channel_id' => $prog->channel_id,
                'start' => date('Y-m-d H:i:s', strtotime($prog->start_date_time)),
                'end' => date('Y-m-d H:i:s', strtotime($prog->end_date_time)),
                'title' => $prog->title,
                'description' => $prog->description,
                'category' => $prog->category
            ];
        }

        return response()->json($response, 200);
    }

    /**
     * Stream Redirector
     *
     * Handles both:
     *   - /get.php?username=X&password=X&stream_id=X  (query-string style)
     *   - /live/{username}/{password}/{stream_id}.{ext} (Xtream URL-rewrite style)
     *   - /movie/{username}/{password}/{stream_id}.{ext}
     *
     * Live channels   → DB id = stream_id (e.g. stream_id=1  → Channel id=1)
     * VOD movies      → DB id = stream_id - 100000 (e.g. stream_id=100002 → Movie id=2)
     */
    public function stream(Request $request, $username = null, $password = null, $streamId = null)
    {
        // Support both query-string and route-param styles
        $username = $username ?? $request->username;
        $password = $password ?? $request->password;
        $streamId = $streamId ?? $request->stream_id ?? $request->id;

        // Validate that the stream_id is numeric
        if (!is_numeric($streamId)) {
            abort(400, 'Invalid stream_id: must be a numeric value');
        }
        $streamId = (int) $streamId;

        // Authenticate — accept both email and user_name
        // The Smarters player uses user_info.username (email) to build stream URLs.
        // Support both so callers using either format work correctly.
        $user = OrgSubscribers::where('email', $username)
            ->orWhere('user_name', $username)
            ->first();
        if (!$user || !password_verify($password, $user->password)) {
            abort(401, 'Authentication failed');
        }

        if (!$streamId) {
            abort(400, 'stream_id is required');
        }

        // Movie IDs are offset by 100000 to avoid clash with channel IDs
        if ($streamId > 100000) {
            $realId = $streamId - 100000;
            $movie = VideoOnDemad::find($realId);
            if ($movie && $movie->streaming_url) {
                return redirect($movie->streaming_url);
            }
            abort(404, 'Movie stream not found');
        }

        // Live channel — redirect to the actual streaming URL
        $channel = Channel::find($streamId);
        if (!$channel) {
            abort(404, 'Channel not found');
        }
        if (!$channel->streaming_url) {
            abort(404, 'Channel has no streaming URL configured');
        }
        return redirect($channel->streaming_url);
    }
}
