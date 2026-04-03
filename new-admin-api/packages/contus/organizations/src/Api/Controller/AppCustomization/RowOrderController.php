<?php

namespace Contus\Organizations\Api\Controller\AppCustomization;

use Contus\Base\ApiController;
use Contus\Base\Repositories\UploadRepository;
use Contus\Channel\Model\Channel;
use Contus\Organizations\Model\ChannelContet;
use Contus\Organizations\Model\LiveEventContent;
use Contus\Organizations\Model\TvShowContent;
use Contus\Organizations\Model\VodContent;
use Contus\Organizations\Repositories\AppCustomization\RowOrderRepository;
use Contus\Tvshow\Model\TvShow;
use Contus\Video\Models\Video;
use Contus\Vod\Model\VideoOnDemad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RowOrderController extends ApiController
{
    protected $_RowOrderbanner;

    public function __construct(RowOrderRepository $rowOrderRepository, UploadRepository $uploadRepository)
    {
        parent::__construct();
        $this->repository = $rowOrderRepository;
        $this->_RowOrderbanner = $uploadRepository;
        $this->repository->setRequestType(static::REQUEST_TYPE);
    }

    public function getInfo()
    {
        return $this->getSuccessJsonResponse(['success']);
    }

    public function postThumbnail()
    {
        $moduleName = 'tvshow-season-episode-image';
        $tempImageInfo = $this->_RowOrderbanner
            ->setModelIdentifier(UploadRepository::MODEL_IDENTIFIER_THUMBNAIL)->tempPrepare()
            ->tempUpload($moduleName, $this->request->size);

        return empty($tempImageInfo) ? $this->getErrorJsonResponse([], trans('video::videos.messsage.resolutionInvalid')) :
            $this->getSuccessJsonResponse(['info' => array_shift($tempImageInfo)]);
    }

    public function postPosters()
    {
        $moduleName = 'tvshow-season-episode-image';
        $tempImageInfo = $this->_RowOrderbanner
            ->setModelIdentifier(UploadRepository::MODEL_IDENTIFIER_POSTER)->tempPrepare()
            ->tempUpload($moduleName, $this->request->size);

        return empty($tempImageInfo) ? $this->getErrorJsonResponse([], trans(StringLiterals::UNABLE_TO_UPLOAD)) :
            $this->getSuccessJsonResponse(['info' => array_shift($tempImageInfo)]);
    }

    public function postAdd()
    {
        $insert = $this->repository->postAdd();
        if ($insert == 'success') {
            return $this->getSuccessJsonResponse(['message' => 'Row Order Created Successfully.']);
        } else {
            return $this->getErrorJsonResponse([], $insert);
        }
    }

    public function saveOrder(Request $request)
    {
        $rowOrder = $this->repository->saveOrder($request);
        if ($rowOrder == 'success') {
            return $this->getSuccessJsonResponse(['message' => 'Row Order Reset Successfully.']);
        } else {
            return $this->getErrorJsonResponse([], $rowOrder);
        }
    }

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
            // dd($assignedLiveEvent, $res);
        } else {
            $res = [];
        }

        return $res;

    }

    // get assigned content from VOD content
    public function getAssignedVodContents(Request $request)
    {
        $orgId = $request->get('organization_id');
        $assignedVodContents = self::getAssignedSets($orgId, 'movies');
        $movies = VideoOnDemad::whereIn('id', $assignedVodContents)->paginate(10);
        return response()->json([
            'error' => false,
            'statusCode' => 200,
            'status' => 'success',
            'message' => null,
            'data' => $movies
        ], 200);
    }

    // get assigned content from Channel content
    public function getAssignedChannelContents(Request $request)
    {
        $orgId = $request->get('organization_id');
        $assignedChannelContents = self::getAssignedSets($orgId, 'channel');
        $channels = Channel::whereIn('id', $assignedChannelContents)->paginate(10);
        return response()->json([
            'error' => false,
            'statusCode' => 200,
            'status' => 'success',
            'message' => null,
            'data' => $channels
        ], 200);
    }

    // get assigned content from Tv Show content
    public function getAssignedTvShowContents(Request $request)
    {
        $orgId = $request->get('organization_id');
        $assignedTvShowContents = self::getAssignedSets($orgId, 'tv_shows');
        $tvShows = TvShow::whereIn('id', $assignedTvShowContents)->paginate(10);
        return response()->json([
            'error' => false,
            'statusCode' => 200,
            'status' => 'success',
            'message' => null,
            'data' => $tvShows
        ], 200);
    }

    // get assigned content from Live Event content
    public function getAssignedLiveEventContents(Request $request)
    {
        $orgId = $request->get('organization_id');
        $assignedLiveEventContents = self::getAssignedSets($orgId, 'live-events');
        $liveEvents = Video::where('organization', $orgId)->whereIn('id', $assignedLiveEventContents)->paginate(10);
        return response()->json([
            'error' => false,
            'statusCode' => 200,
            'status' => 'success',
            'message' => null,
            'data' => $liveEvents
        ], 200);
    }
}
