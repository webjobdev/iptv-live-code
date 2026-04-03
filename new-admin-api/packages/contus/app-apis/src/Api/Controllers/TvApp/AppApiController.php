<?php

namespace Contus\AppApi\Api\Controllers\TvApp;

use Contus\AppApi\Model\ContinueWatching;
use Contus\AppApi\Model\OrganizationPlanPayment;
use Contus\AppApi\Model\SubscriberLike;
use Carbon\Carbon;
use Contus\AppApi\Model\AllowDeviceLogin;
use Contus\AppApi\Model\SubscriberMyList;
use Contus\Base\ApiController;
use Contus\Channel\Model\Channel;
use Contus\ChannelServices\Model\EpgProgram;
use Contus\Organizations\Model\ChannelContet;
use Contus\Organizations\Model\LiveEventContent;
use Contus\Organizations\Model\OrgMonetizationPlanss;
use Contus\Organizations\Model\RowOrder;
use Contus\Organizations\Model\TvShowContent;
use Contus\Organizations\Model\VodContent;
use Contus\ChannelServices\Model\CatchUpIndex;
use Contus\ChannelServices\Model\EpgService;
use Contus\ChannelServices\Model\LiveRewind;
use Contus\GeoBlocking\Model\GeoRestrictions;
use Contus\GeoBlocking\Model\IpRestrictions;
use Contus\Organizations\Model\OrganizationDetail;
use Contus\Settings\Model\PaymentService;
use Contus\Tvshow\Model\SeasonEpisode;
use Contus\Tvshow\Model\TvShow;
use Contus\Video\Models\SeriesCategory;
use Contus\Video\Models\TvCategory;
use Contus\Video\Models\Video;
use Contus\Video\Models\VodCategory;
use Contus\Vod\Model\VideoOnDemad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Omnipay\Omnipay;
use Tymon\JWTAuth\Facades\JWTAuth;
use OpenApi\Annotations as OA;

class AppApiController extends ApiController
{

    public function getBanner(Request $request)
    {

        $user = Auth::user();

        $bannerQuery = OrgMonetizationPlanss::where('banner_carousel_is_active', 1)
            ->where('organization_id', $user->organization_id)
            ->select('auto_scrolling', 'second', 'banners')
            ->orderBy('created_at', 'ASC')
            ->limit(10)
            ->get();

        // Filter banners where banner_is_active = 1
        $bannerQuery->transform(function ($item) {

            // decode JSON if stored as string
            $banners = is_string($item->banners)
                ? json_decode($item->banners, true)
                : $item->banners;

            // keep only active
            $activeBanners = collect($banners)
                ->where('banner_is_active', 1)
                ->values()
                ->toArray();

            $item->banners = $activeBanners;

            return $item;
        });

        return response()->json([
            'success' => true,
            'message' => 'Banner Fetched Successfully',
            'data' => $bannerQuery
        ], 200);
    }

    // get assigned records of channes/vods/live-event/tv-shows
    public static function getAssignedSets($orgId, $category)
    {
        if ($category == 'movies') {
            $assignedMovies = VodContent::where('organization_id', $orgId)
                ->pluck('assigned_vod')
                ->toArray();

            $res = collect($assignedMovies)
                ->flatMap(function ($item) {
                    return collect(json_decode($item, true))->pluck('id');
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
                    return collect(json_decode($item, true))->pluck('id');
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
                    return collect(json_decode($item, true))->pluck('id');
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
                    return collect(json_decode($item, true))->pluck('id');
                })
                ->unique()
                ->values()
                ->toArray();
        } else {
            $res = [];
        }

        return $res;
    }


    public function getHomeScreen(Request $request)
    {
        $user = Auth::user();
        Log::info('user', [$user]);

        if (!$user) {
            return response()->json([
                "success" => false,
                "message" => "User not found"
            ], 404);
        }

        $showData = [];

        /*
        |--------------------------------------------------------------------------
        | Banner Section
        |--------------------------------------------------------------------------
        */
        $bannerQuery = OrgMonetizationPlanss::where('banner_carousel_is_active', 1)
            ->with('bannerSubscription')
            ->where('organization_id', $user->organization_id)
            ->select('id', 'auto_scrolling', 'second', 'banners')
            ->orderBy('created_at', 'ASC')
            ->limit(10)
            ->get();

        $bannerQuery->transform(function ($item) {
            $banners = is_string($item->banners)
                ? json_decode($item->banners, true)
                : $item->banners;

            $activeBanners = collect($banners)
                ->where('banner_is_active', 1)
                ->values()
                ->toArray();

            $subscriptions = collect($item->bannerSubscription);

            foreach ($activeBanners as &$banner) {
                $sub = $subscriptions->where('banner_id', $banner['id'])->first();
                if ($sub) {
                    $subData = $sub->toArray();
                    unset($subData['id']); // Preserve original banner id
                    $banner = array_merge($banner, $subData);
                }
            }

            $item->banners = $activeBanners;
            unset($item->bannerSubscription);

            return $item;
        });

        $showData[] = [
            "title" => "Banner",
            "type" => "banner",
            "data" => $bannerQuery
        ];
        // $bannerQuery = OrgMonetizationPlanss::where('banner_carousel_is_active', 1)
        //     ->where('organization_id', $user->organization_id)
        //     ->select('auto_scrolling', 'second', 'banners')
        //     ->orderBy('created_at', 'ASC')
        //     ->limit(10)
        //     ->get();

        // $bannerQuery->transform(function ($item) {
        //     $banners = is_string($item->banners)
        //         ? json_decode($item->banners, true)
        //         : $item->banners;

        //     $item->banners = collect($banners)
        //         ->where('banner_is_active', 1)
        //         ->values()
        //         ->toArray();

        //     return $item;
        // });

        // $showData[] = [
        //     "title" => "Banner",
        //     "type" => "banner",
        //     "data" => $bannerQuery
        // ];

        /*
        |--------------------------------------------------------------------------
        | Continue Watching Section
        |--------------------------------------------------------------------------
        */
        $movieWatch = ContinueWatching::where('subscriber_id', $user->id)
            ->where('watching_type', 'movie')
            ->with('movie')
            ->get();

        $episodeWatch = ContinueWatching::where('subscriber_id', $user->id)
            ->where('watching_type', 'episode')
            ->with(['Episode.GetTvShow'])
            ->get();

        $showData[] = [
            "title" => "Continue Watching",
            "type" => "continue_watching",
            "data" => $movieWatch->merge($episodeWatch)
        ];

        /*
        |--------------------------------------------------------------------------
        | Dynamic Rows (Movie + TV Show in SAME row)
        |--------------------------------------------------------------------------
        */
        $rows = RowOrder::where('organization_id', $user->organization_id)
            ->orderBy('row_order', 'ASC')
            ->get();

        foreach ($rows as $row) {

            $assigned = is_string($row->assigne_row)
                ? json_decode($row->assigne_row, true)
                : $row->assigne_row;

            $rowData = [];
            $rowTypes = [];

            foreach ($assigned as $item) {

                $rowType = strtolower($item['row_type']);
                $rowDataList = $item['row_data'];

                switch ($rowType) {
                    case 'vod':
                        $model = new VideoOnDemad();
                        $itemType = 'movie';
                        $rowTypes[] = 'Movie';
                        break;

                    case 'tvshow':
                        $model = new TvShow();
                        $itemType = 'tv_show';
                        $rowTypes[] = 'Series';
                        break;

                    case 'liveevent':
                        $model = new Video();
                        $itemType = 'live_event';
                        $rowTypes[] = 'Live Event';
                        break;

                    case 'channel':
                        $model = new Channel();
                        $itemType = 'channel';
                        $rowTypes[] = 'Channel';
                        break;

                    default:
                        continue 2;
                }

                foreach ($rowDataList as $r) {
                    $record = $model->where('id', $r['id'])
                        ->whereHas('getAllOrganization', function ($query) use ($user) {
                            $query->where('organization_id', $user->organization_id);
                        })
                        // ->where('organization', $user->organization_id)
                        ->first();

                    if ($record) {
                        $rowData[] = array_merge(
                            ['type' => $itemType],
                            $record->toArray()
                        );
                    }
                }
            }

            if (!empty($rowData)) {
                $showData[] = [
                    "title" => $row->title,
                    "slug" => Str::slug($row->title),
                    "type" => implode(', ', array_unique($rowTypes)), // Movie, Series
                    "data" => $rowData
                ];
            }
        }

        return response()->json([
            "success" => true,
            "message" => "Data Fetched Successfully.",
            "data" => $showData
        ]);
    }

    // public function getHomeScreen(Request $request)
    // {
    //     $user = Auth::user();

    //     if (!$user) {
    //         return response()->json([
    //             "success" => false,
    //             "message" => "User not found"
    //         ], 404);
    //     }

    //     $ipData = \Location::get('2409:40c1:10b6:cc3b:148e:cbff:fe07:1b96');
    //     // $ipData = \Location::get($user->ip_address);
    //     $userCountry = $ipData->countryName ?? null;

    //     $showData = [];

    //     /*
    //     |--------------------------------------------------------------------------
    //     | Banner Section
    //     |--------------------------------------------------------------------------
    //     */
    //     $bannerQuery = OrgMonetizationPlanss::where('banner_carousel_is_active', 1)
    //         ->where('organization_id', $user->organization_id)
    //         ->select('auto_scrolling', 'second', 'banners')
    //         ->orderBy('created_at', 'ASC')
    //         ->limit(10)
    //         ->get();

    //     $bannerQuery->transform(function ($item) {
    //         $banners = is_string($item->banners)
    //             ? json_decode($item->banners, true)
    //             : $item->banners;

    //         $item->banners = collect($banners)
    //             ->where('banner_is_active', 1)
    //             ->values()
    //             ->toArray();

    //         return $item;
    //     });

    //     $showData[] = [
    //         "title" => "Banner",
    //         "type" => "banner",
    //         "data" => $bannerQuery
    //     ];

    //     /*
    //     |--------------------------------------------------------------------------
    //     | Continue Watching Section
    //     |--------------------------------------------------------------------------
    //     */
    //     $movieWatch = ContinueWatching::where('watching_type', 'movie')
    //         ->with('movie')
    //         ->get();

    //     $episodeWatch = ContinueWatching::where('watching_type', 'episode')
    //         ->with(['Episode.GetTvShow'])
    //         ->get();

    //     $showData[] = [
    //         "title" => "Continue Watching",
    //         "type" => "continue_watching",
    //         "data" => $movieWatch->merge($episodeWatch)
    //     ];

    //     /*
    //     |--------------------------------------------------------------------------
    //     | Dynamic Rows (Movie + TV Show in SAME row)
    //     |--------------------------------------------------------------------------
    //     */
    //     $rows = RowOrder::where('organization_id', $user->organization_id)
    //         ->orderBy('row_order', 'ASC')
    //         ->get();

    //     foreach ($rows as $row) {

    //         $assigned = is_string($row->assigne_row)
    //             ? json_decode($row->assigne_row, true)
    //             : $row->assigne_row;

    //         $rowData = [];
    //         $rowTypes = [];

    //         foreach ($assigned as $item) {

    //             $rowType = strtolower($item['row_type']);
    //             $rowDataList = $item['row_data'];

    //             switch ($rowType) {
    //                 case 'vod':
    //                     $model = new VideoOnDemad();
    //                     $itemType = 'movie';
    //                     $rowTypes[] = 'Movie';
    //                     break;

    //                 case 'tvshow':
    //                     $model = new TvShow();
    //                     $itemType = 'tv_show';
    //                     $rowTypes[] = 'Series';
    //                     break;

    //                 case 'liveevent':
    //                     $model = new Video();
    //                     $itemType = 'live_event';
    //                     $rowTypes[] = 'Live Event';
    //                     break;

    //                 case 'channel':
    //                     $model = new Channel();
    //                     $itemType = 'channel';
    //                     $rowTypes[] = 'Channel';
    //                     break;

    //                 default:
    //                     continue 2;
    //             }

    //             foreach ($rowDataList as $r) {
    //                      $record = $model->where('id', $r['id'])
    //                          ->where('organization', $user->organization_id)
    //                          ->first();

    //                      if ($itemType == 'channel') {
    //                      $record = $model->where('id', $r['id'])
    //                     ->where('organization', $user->organization_id)
    //                     ->where(function ($query) use ($userCountry) {
    //                         $query->where('geo_blocking', 0)
    //                             ->orWhere(function ($q) use ($userCountry) {
    //                                 $q->where('geo_blocking', 1)
    //                                     ->whereJsonDoesntContain(
    //                                         'geo_block_country_list',
    //                                         strtolower($userCountry)
    //                                     );
    //                             });
    //                     })
    //                     ->first();
    //                  } 
    // else if ($itemType == 'tv_show' || $itemType == 'movie') {
    //                         $record = $model->where('id', $r['id'])
    //                     ->where('organization', $user->organization_id)
    //                     ->where(function ($query) use ($userCountry) {
    //                         $query->where('geo_policy', 0)
    //                             ->orWhere(function ($q) use ($userCountry) {
    //                                 $q->where('geo_policy', 1)
    //                                     ->whereJsonDoesntContain(
    //                                         'geo_block_country_list',
    //                                         strtolower($userCountry)
    //                                     );
    //                             });
    //                     })
    //                     ->first();
    //                    }
    // else if ($itemType == 'live-event') {
    //                         $record = $model->where('id', $r['id'])
    //                         ->where('organization', $user->organization_id)
    //                         ->first();
    //                    }
    //                 if ($record) {
    //                     $rowData[] = array_merge(
    //                         ['type' => $itemType],
    //                         $record->toArray()
    //                     );
    //                 }
    //             }
    //         }

    //         if (!empty($rowData)) {
    //             $showData[] = [
    //                 "title" => $row->title,
    //                 "slug" => Str::slug($row->title),
    //                 "type" => implode(', ', array_unique($rowTypes)), // Movie, Series
    //                 "data" => $rowData
    //             ];
    //         }
    //     }

    //     return response()->json([
    //         "success" => true,
    //         "message" => "Data Fetched Successfully.",
    //         "data" => $showData
    //     ]);
    // }


    public function MovieList(Request $request)
    {
        /* ------------------------------------------------
           1. Validation
        -------------------------------------------------*/
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:movie,tv_show,series',
            'category' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 400);
        }

        /* ------------------------------------------------
           2. Auth User
        -------------------------------------------------*/
        $user = Auth::user();
        // dd($user);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        /* ------------------------------------------------
           3. Geo / IP Detection
        -------------------------------------------------*/
        // Use real user IP in production
        // $ipData = \Location::get($request->ip());
        $ipData = \Location::get('2409:40c1:10b6:cc3b:148e:cbff:fe07:1b96');

        $userCountry = strtolower($ipData->countryName ?? '');

        /* ------------------------------------------------
           4. Allowed Row Types
        -------------------------------------------------*/
        $allowedRowTypes = ($request->type === 'movie') ? ['vod'] : ['tvshow'];

        /* ------------------------------------------------
           5. Fetch Rows
        -------------------------------------------------*/
        $rows = RowOrder::where('organization_id', $user->organization_id)
            ->orderBy('row_order', 'ASC')
            ->get();

        $showData = [];

        /* ------------------------------------------------
           6. Build Rows Data
        -------------------------------------------------*/
        foreach ($rows as $row) {

            $assignedRows = is_string($row->assigne_row)
                ? json_decode($row->assigne_row, true)
                : $row->assigne_row;

            if (empty($assignedRows)) {
                continue;
            }

            $rowData = [];
            $rowTypes = [];

            foreach ($assignedRows as $item) {

                $rowType = strtolower($item['row_type'] ?? '');

                if (!in_array($rowType, $allowedRowTypes)) {
                    continue;
                }

                /* ------------------------------------------------
                   7. Model Mapping
                -------------------------------------------------*/
                if ($rowType === 'vod') {
                    $model = new VideoOnDemad();
                    $itemType = 'movie';
                    $rowTypes[] = 'Movie';
                } elseif ($rowType === 'tvshow') {
                    $model = new TvShow();
                    $itemType = 'series';
                    $rowTypes[] = 'Series';
                } else {
                    continue;
                }

                $rowDataList = $item['row_data'] ?? [];

                /* ------------------------------------------------
                   8. Fetch Row Items
                -------------------------------------------------*/
                foreach ($rowDataList as $r) {

                    if (!isset($r['id'])) {
                        continue;
                    }

                    $record = $model->where('id', $r['id'])
                        ->whereHas('getAllOrganization', function ($query) use ($user) {
                            $query->where('organization_id', $user->organization_id);
                        })
                        // ->where('organization', $user->organization_id)

                        /* -------- CATEGORY FILTER -------- */
                        ->when($request->filled('category'), function ($query) use ($request) {
                            $category = strtolower(trim($request->category));

                            $query->whereJsonContains('category', ucfirst($category));
                        })

                        /* -------- GEO BLOCKING -------- */
                        ->where(function ($query) use ($userCountry) {
                            $query->where('geo_policy', 0)
                                ->orWhere(function ($q) use ($userCountry) {
                                    $q->where('geo_policy', 1)
                                        ->whereJsonDoesntContain(
                                            'geo_block_country_list',
                                            $userCountry
                                        );
                                });
                        })
                        ->first();

                    if (!$record) {
                        continue;
                    }

                    /* ------------------------------------------------
                       9. Like / My List Status
                    -------------------------------------------------*/
                    if ($itemType === 'movie') {
                        $record->my_list_status = SubscriberMyList::where('subscriber_id', $user->id)
                            ->where('movie_id', $record->id)
                            ->exists();

                        $record->my_like_status = SubscriberLike::where('subscriber_id', $user->id)
                            ->where('movie_id', $record->id)
                            ->exists();
                    } else {
                        $record->my_list_status = SubscriberMyList::where('subscriber_id', $user->id)
                            ->where('series_id', $record->id)
                            ->exists();

                        $record->my_like_status = SubscriberLike::where('subscriber_id', $user->id)
                            ->where('series_id', $record->id)
                            ->exists();
                    }

                    $rowData[] = array_merge(
                        ['type' => $itemType],
                        $record->toArray()
                    );
                }
            }

            /* ------------------------------------------------
               10. Push Row Only If Data Exists
            -------------------------------------------------*/
            if (!empty($rowData)) {
                $showData[] = [
                    'title' => $row->title,
                    'slug' => Str::slug($row->title),
                    'type' => implode(', ', array_unique($rowTypes)),
                    'data' => $rowData
                ];
            }
        }

        /* ------------------------------------------------
           11. Final Response
        -------------------------------------------------*/
        if (empty($showData)) {
            return response()->json([
                'error' => false,
                'statusCode' => 404,
                'status' => 'Success',
                'message' => 'Data not found',
                'data' => []
            ], 200);
        }

        return response()->json([
            'error' => false,
            'statusCode' => 200,
            'status' => 'Success',
            'message' => 'Data Fetch Successfully',
            'data' => $showData
        ], 200);
    }

    public function MovieDetail(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 400);
        }

        $user = Auth::user();

        // Fetch movie detail with organization match
        // $movieDetail = VideoOnDemad::where('id', $request->id)
        //     ->where('organization', $user->organization_id)
        //     ->with(['ContinueWatching'])
        //     ->first();

        $movieDetail = VideoOnDemad::where('id', $request->id)
            ->whereHas('getAllOrganization', function ($query) use ($user) {
                $query->where('organization_id', $user->organization_id);
            })
            ->with(['ContinueWatching'])
            ->first();

        if (!$movieDetail) {
            return response()->json([
                "error" => true,
                "message" => "Movie not found",
                "statusCode" => 404
            ], 404);
        }

        //  dd(is_array($movieDetail->category));
        // Decode category for matching
        $categories = is_array($movieDetail->category) ? is_array($movieDetail->category) : json_decode($movieDetail->category, true);
        // dd($categories);

        if (!is_array($categories)) {
            $categories = [];
        }

        // Fetch More Like This (same category)
        $moreLikeThis = VideoOnDemad::where('organization', $user->organization_id)
            ->where('id', '!=', $request->id)
            ->where(function ($query) use ($categories) {
                foreach ($categories as $cat) {
                    $query->orWhere('category', 'LIKE', "%$cat%");
                }
            })
            ->limit(15)
            ->get();

        // Add more_like_this to main response
        $movieDetail->more_like_this = $moreLikeThis;

        $isWatchlist = SubscriberMyList::where('subscriber_id', $user->id)
            ->where('movie_id', $request->id)
            ->exists();

        $isWatch = SubscriberLike::where('subscriber_id', $user->id)
            ->where('movie_id', $request->id)
            ->exists();

        // $watch_duration = ContinueWatching::where('subscriber_id', $user->id)
        //     ->where('watching_type', 'movie')
        //     ->where('watchable_id', $request->id)
        //     ->value('watched_duration');

        $movieDetail->my_list_status = $isWatchlist ? true : false;
        $movieDetail->my_like_status = $isWatch ? true : false;
        // $movieDetail->watch_duration = $watch_duration ? $watch_duration : 0;

        return response()->json([
            "error" => false,
            "status" => "success",
            "message" => 'Movie Detail Fetched Successfully',
            "data" => $movieDetail,
        ], 200);
    }


    public function MovieCategory(Request $request)
    {
        // dd(123);
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'error' => true,
                'message' => 'User Not Found.',
            ], 400);
        }

        $categories = VodCategory::whereHas('getOrganization', function ($query) use ($user) {
            $query->where('organization_id', $user->organization_id);
        })
            ->with(['categories.getSubCategory', 'getOrganization'])
            ->whereNull('categorie_id')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Vod Categories Data Fetch.',
            'data' => $categories
        ], 200);
    }


    public function SeasonDetail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 400);
        }

        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorised'
            ], 401);
        }

        // Fetch TV Show with seasons & episodes

        // $seasonDetail = TvShow::where('id', $request->id)
        //     ->where('organization', $user->organization_id)
        //     ->with(['getSeasons.getEpisodes.ContinueWatching'])
        //     ->first();

        $seasonDetail = TvShow::where('id', $request->id)
            ->whereHas('getAllOrganization', function ($query) use ($user) {
                $query->where('organization_id', $user->organization_id);
            })
            ->with(['getSeasons.getEpisodes.ContinueWatching'])
            ->first();

        if (!$seasonDetail) {
            return response()->json([
                "error" => true,
                "message" => "Season not found",
                "statusCode" => 404
            ], 404);
        }

        // Fetch trailer from another TV show (same org)
        $trailer = TvShow::where('organization', $user->organization_id)
            ->where('id', '!=', $request->id)
            ->value('trailer_url');

        // Attach trailer data
        $seasonDetail->trailers = [
            'trailer_url' => $trailer
        ];

        // Check watchlist
        $isWatchlist = SubscriberMyList::where('subscriber_id', $user->id)
            ->where('series_id', $request->id)
            ->exists();

        // check mylike 
        $isWatch = SubscriberLike::where('subscriber_id', $user->id)
            ->where('series_id', $request->id)
            ->exists();

        // $watch_duration = ContinueWatching::where('subscriber_id', $user->id)
        //     ->where('watching_type', 'episode')
        //     ->where('watchable_id', $request->id)
        //     ->value('watched_duration');

        // ✅ FIX: Attach property to MODEL (not collection)
        $seasonDetail->my_list_status = $isWatchlist ? true : false;
        $seasonDetail->my_like_status = $isWatch ? true : false;
        // $seasonDetail->watch_duration = $watch_duration ? $watch_duration : 0;

        return response()->json([
            "error" => false,
            "statusCode" => 200,
            "status" => "success",
            "message" => "Season Detail Fetch.",
            "data" => $seasonDetail
        ], 200);
    }


    public function checkDeviceLogin(Request $request)
    {
        $validaor = Validator::make($request->all(), [
            'device_id' => 'required|string',
            'device_type' => 'required',
            'plan_id' => 'required|exists:org_monetization_planss,id'
        ]);

        if ($validaor->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validaor->errors()->first()
            ], 400);
        }


        $user = Auth::user();

        $plan = OrgMonetizationPlanss::find($request->plan_id);
        if (!$plan) {
            return response()->json([
                'success' => false,
                'message' => 'Plan not found'
            ], 404);
        }

        $platformsCount = count($plan->platforms);
        $deviceLogin = AllowDeviceLogin::where('subscriber_id', $user->id)->count();

        if ($platformsCount <= $deviceLogin) {
            return response()->json([
                'success' => true,
                'message' => 'You have reached maximum device login limit. To Login please logout from existing devices.'
            ], 200);
        }

        $newDeviceLogin = new AllowDeviceLogin();
        $newDeviceLogin->subscriber_id = $user->id;
        $newDeviceLogin->device_id = $request->device_id;
        $newDeviceLogin->device_type = $request->device_type;
        $newDeviceLogin->last_login_at = Carbon::now();
        $newDeviceLogin->save();

        return response()->json([
            'success' => true,
            'can_login' => true,
            'message' => 'Login Successfull.'
        ], 200);
    }


    public function ContinueWatching(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required',
            'record_id' => 'required|integer',
            'watched_duration' => 'required|integer',
            'total_duration' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 400);
        }


        $user = Auth::user();

        if ($request->type == 'movie') {
            $item = VideoOnDemad::find($request->record_id);
        } elseif ($request->type == 'episode') {
            $item = SeasonEpisode::find($request->record_id);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Invalid type provided'
            ], 400);
        }

        if (!$item) {
            return response()->json([
                'success' => false,
                'message' => 'Type not found'
            ], 404);
        }

        if ($request->type === 'movie') {
            $videoFielddd = ['watchable_id' => $request->record_id];
            $videoField = ['watching_type' => $request->type];
        } elseif ($request->type === 'episode') {
            $videoFielddd = ['watchable_id' => $request->record_id];
            $videoField = ['watching_type' => $request->type];
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Invalid type'
            ], 400);
        }

        $watching = ContinueWatching::updateOrCreate(
            array_merge([
                'subscriber_id' => $user->id
            ], $videoField, $videoFielddd),
            [
                'watched_duration' => $request->watched_duration,
                'total_duration' => $request->total_duration,
                'is_completed' => $request->watched_duration >= $request->total_duration
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Progress saved successfully',
            'data' => $watching,
        ], 200);
    }

    public function removeContinueWatching(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'id' => 'required'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validate->errors()->first()
            ], 400);
        }

        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        $continueWatching = ContinueWatching::where('subscriber_id', $user->id)
            ->where('id', $request->id)
            ->first();

        if (!$continueWatching) {
            return response()->json([
                'success' => false,
                'message' => 'Continue Watching not found'
            ], 404);
        }

        $continueWatching->delete();

        return response()->json([
            'success' => true,
            'message' => 'Continue Watching deleted successfully',
        ], 200);
    }

    public function fetchContinueWatching(Request $request)
    {

        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        $moviewatch = ContinueWatching::where('watching_type', 'movie')
            ->with('movie')
            ->get();

        $Episodewatch = ContinueWatching::where('watching_type', 'episode')
            ->with(['Episode.GetTvShow'])
            ->get();

        $continueWatch = [
            // [
            ...$moviewatch->toArray(),
            ...$Episodewatch->toArray()
            // ]
        ];

        return response()->json([
            'success' => true,
            'message' => 'Continue Watching Fetch.',
            'data' => $continueWatch,
        ], 200);
    }


    public function getPaymentList(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        $paymentList = PaymentService::get();

        return response()->json([
            'success' => true,
            'message' => 'Payment List Fetched Successfully.',
            'data' => $paymentList,
        ], 200);
    }

    public function fetchLiveChannelDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 400);
        }

        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User Not Found.',
            ], 400);
        }

        // Fetch channel
        // $channel = Channel::where('id', $request->id)
        //     ->where('organization', $user->organization_id)
        //     ->first();

        $channel = Channel::where('id', $request->id)
            ->whereHas('getAllOrganization', function ($query) use ($user) {
                $query->where('organization_id', $user->organization_id);
            })
            ->first();

        if (!$channel) {
            return response()->json([
                'success' => false,
                'message' => 'Channel not found in your organization.',
            ], 404);
        }

        // Fetch EPG data
        $epgData = EpgProgram::where('channel_id', $channel->id)->get();

        // Always attach epg (even if empty)
        $channel->epg = $epgData;

        // ✅ Always return 200 if channel exists
        return response()->json([
            'success' => true,
            'message' => $epgData->isEmpty()
                ? 'Live Channel Detail Data Fetched (EPG not available).'
                : 'Live Channel Detail Data Fetched.',
            'data' => $channel,
        ], 200);
    }

    // fetch channel category wise & call only channel both
    public function fetchLiveChannel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 400);
        }

        $user = Auth::user();
        $ipData = \Location::get($user->ip_address);
        $userCountry = ($ipData && !empty($ipData->countryName)) ? strtolower($ipData->countryName) : (($ipData && !empty($ipData->country)) ? strtolower($ipData->country) : null);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User Not Found.'
            ], 401);
        }

        /* --------------------------------
         | NO CATEGORY → ALL CHANNELS
         --------------------------------*/
        if (!$request->category) {

            $channels = ChannelContet::where('organization_id', $user->organization_id)
                ->pluck('assigned_channels')
                ->toArray();

            $res = collect($channels)
                ->flatMap(function ($item) {
                    return collect(json_decode($item, true))->pluck('id');
                })
                ->unique()
                ->values()
                ->toArray();

            $channels = Channel::whereIn('id', $res)
                ->where(function ($query) use ($userCountry) {
                    $query->where('geo_policy', 0)
                        ->orWhere(function ($q) use ($userCountry) {
                            $q->where('geo_policy', 1)
                                ->whereJsonDoesntContain('geo_block_country_list', $userCountry);
                        });
                })
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Channel data fetch successfully.',
                'data' => $channels
            ]);
        }

        /* --------------------------------
         | CATEGORY BASED FETCH
         --------------------------------*/
        $category = TvCategory::whereHas('getOrganization', function ($query) use ($user) {
            $query->where('organization_id', $user->organization_id);
        })
            ->where('tv_categorie_name', $request->category)
            ->with([
                'children.subCategories.getChannel' => function ($q) use ($user) {
                    $q->whereHas('getAllOrganization', function ($sub) use ($user) {
                        $sub->where('organization_id', $user->organization_id);
                    });
                }
            ])
            ->first();

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found'
            ], 404);
        }

        $channels = [];

        foreach ($category->children as $subCategory) {
            foreach ($subCategory->subCategories as $channelRow) {

                if (!$channelRow->getChannel) {
                    continue;
                }

                $channel = $channelRow->getChannel;

                if ($channel->organization != $user->organization_id) {
                    continue;
                }

                $data = $channel->toArray();
                $data['category'] = $request->category;

                $channels[] = $data;
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Live Channel Data Fetch.',
            'data' => $channels
        ]);
    }

    public function liveChannelCategories(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'error' => true,
                'message' => 'User Not Found.',
            ], 400);
        }

        $categories = TvCategory::with([
            'categorie_id' => function ($q) {
                $q->with([
                    'get_sub_category' => function ($sub) {
                        $sub->with('getChannel');
                        // $sub->makeHidden('content_sets');
                    }
                ]);
            },
            'getOrganization'
        ])
            ->whereNull('categorie_id')
            ->whereNull('sub_category_id')
            ->whereNull('channel_id')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Live Channel Categories Data Fetch.',
            'data' => $categories
        ], 200);
    }

    public function searchRecords(Request $request)
    {
        $user = Auth::user();
        $ipData = \Location::get($user->ip_address);
        $userCountry = ($ipData && !empty($ipData->countryName)) ? strtolower($ipData->countryName) : (($ipData && !empty($ipData->country)) ? strtolower($ipData->country) : null);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User Not Found.'
            ], 400);
        }

        $responseRows = [];

        $rowOrders = RowOrder::where('organization_id', $user->organization_id)
            ->orderBy('id', 'ASC')->get();


        // $rowOrders = RowOrder::whereHas('getAllOrganization', function ($query) use ($user) {
        //     $query->where('organization_id', $user->organization_id);
        // })
        //     ->orderBy('id', 'ASC')->get();

        foreach ($rowOrders as $rowOrder) {
            $rowData = collect();
            foreach ($rowOrder->assigne_row as $row) {
                switch ($row['row_type']) {
                    case 'channel':
                        $channelIds = collect($row['row_data'])->pluck('id')->toArray();
                        $channels = Channel::whereIn('id', $channelIds)->where('channel_name', 'like', '%' . $request->value . '%')
                            ->where(function ($query) use ($userCountry) {
                                $query->where('geo_policy', 0)
                                    ->orWhere(function ($q) use ($userCountry) {
                                        $q->where('geo_policy', 1)
                                            ->whereJsonDoesntContain('geo_block_country_list', $userCountry);
                                    });
                            })->get()
                            ->each(function ($item) {
                                $item->type = 'channel';
                                return $item;
                            });
                        $rowData = $rowData->merge($channels);
                        break;

                    case 'vod':
                        $ids = collect($row['row_data'])->pluck('id')->toArray();
                        $movies = VideoOnDemad::whereIn('id', $ids)->where('title', 'like', '%' . $request->value . '%')
                            ->where(function ($query) use ($userCountry) {
                                $query->where('geo_policy', 0)
                                    ->orWhere(function ($q) use ($userCountry) {
                                        $q->where('geo_policy', 1)
                                            ->whereJsonDoesntContain('geo_block_country_list', $userCountry);
                                    });
                            })->get()
                            ->each(function ($item) {
                                $item->type = 'movie';
                                return $item;
                            });

                        $rowData = $rowData->merge($movies);
                        break;

                    case 'tvshow':
                        $tvShowIds = collect($row['row_data'])->pluck('id')->toArray();
                        $series = TvShow::whereIn('id', $tvShowIds)->where('title', 'like', '%' . $request->value . '%')
                            ->where(function ($query) use ($userCountry) {
                                $query->where('geo_policy', 0)
                                    ->orWhere('geo_policy', 1)
                                    ->whereJsonDoesntContain('geo_block_country_list', $userCountry);
                            })->get()
                            ->each(function ($item) {
                                $item->type = 'series';
                                return $item;
                            });
                        $rowData = $rowData->merge($series);
                        break;

                    case 'liveevent':
                        $leventIds = collect($row['row_data'])->pluck('id')->toArray();
                        $lEvents = Video::where('organization', $user->organization_id)
                            ->whereIn('id', $leventIds)
                            ->where('title', 'like', '%' . $request->value . '%')
                            // ->where(function ($query) use ($userCountry) {
                            //     $query->where('geo_policy', 0);
                            //     if ($userCountry) {
                            //         $query->orWhere(function ($q) use ($userCountry) {
                            //             $q->where('geo_policy', 1)
                            //                 ->whereJsonDoesntContain('geo_block_country_list', $userCountry);
                            //         });
                            //     }
                            // })
                            ->get()
                            ->map(function ($item) {
                                $item->type = 'live-event';
                                return $item;
                            });
                        $rowData = $rowData->merge($lEvents);
                        break;
                }
            }
            $responseRows[] = [
                'title' => $rowOrder->title,
                'slug' => Str::slug($rowOrder->title),
                'type' => $rowOrder->type,
                'data' => $rowData->values()
            ];
        }

        return response()->json([
            'success' => true,
            'message' => 'Search records fetched successfully',
            'data' => $responseRows,
        ], 200);
    }


    public function getGenresList(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'nullable|in:movie,series',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 400);
        }

        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User Not Found.'
            ], 400);
        }

        // Initialize variable to avoid undefined error
        $query = collect([]);

        if ($request->type === 'movie') {
            $query = VodCategory::whereHas('getOrganization', function ($query) use ($user) {
                $query->where('organization_id', $user->organization_id);
            })->get();
        } elseif ($request->type === 'series') {
            $query = SeriesCategory::whereHas('getOrganization', function ($query) use ($user) {
                $query->where('organization_id', $user->organization_id);
            })->get();
        } else {
            // default: return both movie + series
            $query = [
                'movie' => VodCategory::whereHas('getOrganization', function ($query) use ($user) {
                    $query->where('organization_id', $user->organization_id);
                })->get(),
                'series' => SeriesCategory::whereHas('getOrganization', function ($query) use ($user) {
                    $query->where('organization_id', $user->organization_id);
                })->get(),
            ];

            return response()->json([
                "status" => "success",
                "message" => "Category Fetch Successfully",
                "data" => $query
            ], 200);
        }

        if ($query->isEmpty()) {
            return response()->json([
                "error" => true,
                "message" => "Data not found",
                "statusCode" => 404
            ], 404);
        }

        return response()->json([
            "status" => "success",
            "message" => "Category Fetch Successfully",
            "data" => $query
        ], 200);
    }

    public function viewAll(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'slug' => 'required|string',
            'type' => 'nullable|in:movie,series',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 400);
        }

        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User Not Found.'
            ], 401);
        }

        /* -------------------------------
           GEO LOCATION
        --------------------------------*/
        $ipData = \Location::get($user->ip_address);
        $userCountry = ($ipData && !empty($ipData->country))
            ? strtolower($ipData->country)
            : null;

        /* -------------------------------
           TYPE FILTER (PHP 7 SAFE)
        --------------------------------*/
        $type = $request->type ? strtolower($request->type) : null;

        if ($type === 'movie') {
            $allowedRowTypes = ['vod'];
        } elseif ($type === 'series') {
            $allowedRowTypes = ['tvshow'];
        } else {
            $allowedRowTypes = ['vod', 'tvshow', 'liveevent', 'channel'];
        }

        /* -------------------------------
           FETCH ROWS BY SLUG
        --------------------------------*/
        $rows = RowOrder::where('organization_id', $user->organization_id)
            ->orderBy('row_order', 'ASC')
            ->get()
            ->filter(function ($row) use ($request) {
                return Str::slug($row->title) === $request->slug;
            });
        // $rows = RowOrder::whereHas('getAllOrganization', function ($query) use ($user) {
        //     $query->where('organization_id', $user->organization_id);
        // })
        //     ->orderBy('row_order', 'ASC')
        //     ->get()
        //     ->filter(function ($row) use ($request) {
        //         return Str::slug($row->title) === $request->slug;
        //     });

        $showData = [];

        foreach ($rows as $row) {

            $assignedRows = is_string($row->assigne_row)
                ? json_decode($row->assigne_row, true)
                : $row->assigne_row;

            if (!is_array($assignedRows)) {
                continue;
            }

            $rowData = [];

            foreach ($assignedRows as $item) {

                $rowType = isset($item['row_type'])
                    ? strtolower($item['row_type'])
                    : '';

                // 🔒 TYPE FILTER APPLIED
                if (!in_array($rowType, $allowedRowTypes)) {
                    continue;
                }

                $rowDataList = isset($item['row_data']) && is_array($item['row_data'])
                    ? $item['row_data']
                    : [];

                switch ($rowType) {
                    case 'vod':
                        $model = new VideoOnDemad();
                        $itemType = 'movie';
                        $rowTypes[] = 'movie';
                        break;

                    case 'tvshow':
                        $model = new TvShow();
                        $itemType = 'series';
                        $rowTypes[] = 'series';
                        break;

                    case 'liveevent':
                        $model = new Video();
                        $itemType = 'live_event';
                        $rowTypes[] = 'live_event';
                        break;

                    case 'channel':
                        $model = new Channel();
                        $itemType = 'channel';
                        $rowTypes[] = 'channel';
                        break;

                    default:
                        continue 2;
                }

                foreach ($rowDataList as $r) {

                    if (!isset($r['id'])) {
                        continue;
                    }

                    /* -------------------------------
                       FETCH RECORD WITH GEO BLOCK
                    --------------------------------*/
                    // $query = $model->where('id', $r['id'])
                    //     ->where('organization', $user->organization_id);

                    $query = $model->where('id', $r['id'])
                        ->whereHas('getAllOrganization', function ($query) use ($user) {
                            $query->where('organization_id', $user->organization_id);
                        });

                    if ($itemType === 'movie' || $itemType === 'series' || $itemType === 'channel') {
                        $query->where(function ($q) use ($userCountry) {
                            $q->where('geo_policy', 0);

                            if ($userCountry) {
                                $q->orWhere(function ($subQ) use ($userCountry) {
                                    $subQ->where('geo_policy', 1)
                                        ->whereJsonDoesntContain('geo_block_country_list', $userCountry);
                                });
                            }
                        });
                    }

                    $record = $query->first();

                    if (!$record) {
                        continue;
                    }

                    /* -------------------------------
                       MY LIST & LIKE STATUS
                    --------------------------------*/
                    if ($itemType === 'movie') {
                        $record->my_list_status = SubscriberMyList::where('subscriber_id', $user->id)
                            ->where('movie_id', $record->id)
                            ->exists();

                        $record->my_like_status = SubscriberLike::where('subscriber_id', $user->id)
                            ->where('movie_id', $record->id)
                            ->exists();
                    } else {
                        $record->my_list_status = SubscriberMyList::where('subscriber_id', $user->id)
                            ->where('series_id', $record->id)
                            ->exists();

                        $record->my_like_status = SubscriberLike::where('subscriber_id', $user->id)
                            ->where('series_id', $record->id)
                            ->exists();
                    }

                    $rowData[] = array_merge(
                        ['type' => $itemType],
                        $record->toArray()
                    );
                }
            }

            if (!empty($rowData)) {
                $showData[] = [
                    'title' => $row->title,
                    "type" => implode(', ', array_unique($rowTypes)),
                    'data' => $rowData
                ];
            }
        }

        if (empty($showData)) {
            return response()->json([
                'error' => false,
                'statusCode' => 404,
                'status' => 'Success',
                'message' => 'Data not found',
                'data' => []
            ], 200);
        }

        return response()->json([
            'error' => false,
            'statusCode' => 200,
            'status' => 'Success',
            'message' => 'Data Fetch Successfully',
            'data' => $showData
        ], 200);
    }

    public function LiveEventDetail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 400);
        }

        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorised'
            ], 401);
        }

        $liveEventDetail = Video::where('id', $request->id)
            ->whereHas('getAllOrganization', function ($query) use ($user) {
                $query->where('organization_id', $user->organization_id);
            })
            ->first();

        if (!$liveEventDetail) {
            return response()->json([
                "error" => true,
                "message" => "Live Event not found",
                "statusCode" => 404
            ], 404);
        }

        // Check watchlist
        $isWatchlist = SubscriberMyList::where('subscriber_id', $user->id)
            ->where('channel_id', $request->id)
            ->exists();

        // check mylike 
        $isWatch = SubscriberLike::where('subscriber_id', $user->id)
            ->where('channel_id', $request->id)
            ->exists();

        $liveEventDetail->my_list_status = $isWatchlist ? true : false;
        $liveEventDetail->my_like_status = $isWatch ? true : false;

        return response()->json([
            "error" => false,
            "statusCode" => 200,
            "status" => "success",
            "message" => "Live Event Detail Fetch.",
            "data" => $liveEventDetail
        ], 200);
    }
}
