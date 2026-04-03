<?php

namespace Contus\Organizations\Repositories\AppCustomization;

use Contus\Base\Repository;
use Contus\Organizations\Model\OrgMonetizationPlanss;
use Contus\Organizations\Model\OrgMonetznSubsriptionContentSets;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AppCustomiztionFeaturedRowRepository extends Repository
{
    protected $_OrgMonetznSubsriptionContentSets;
    protected $_OrgMonetizationPlanss;

    public function __construct(OrgMonetizationPlanss $OrgMonetizationPlanss, OrgMonetznSubsriptionContentSets $orgMonetznSubsriptionContentSets)
    {
        parent::__construct();
        $this->_OrgMonetizationPlanss = $OrgMonetizationPlanss;
        $this->_OrgMonetznSubsriptionContentSets = $orgMonetznSubsriptionContentSets;
    }

    public function postEdit($id)
    {
        $user = Auth::user();
        $plan = $this->_OrgMonetizationPlanss->find($id);

        $plan->created_by = $user->id;
        $plan->platforms = $this->request->input('platforms') ?? null;
        $plan->save();

        $contentSet = $this->_OrgMonetznSubsriptionContentSets->find($id);

        // Get input data
        $channelSet = $this->request->input('channelDataSet', []);
        $vodSet = $this->request->input('vodData', []);
        $tvShowSet = $this->request->input('tvShowContentSet', []);

        $mergeIds = function ($existing, $newItems) {
            $existingArray = is_array($existing) ? $existing : json_decode($existing, true);
            $existingArray = is_array($existingArray) ? $existingArray : [];

            $newIds = array_map(function ($item) {
                return $item['id'];
            }, $newItems);

            return array_values(array_unique(array_merge($existingArray, $newIds)));
        };

        if ($channelSet) {
            $contentSet->channel_id = $mergeIds($contentSet->channel_id, $channelSet);
        }

        if ($vodSet) {
            $contentSet->vod_id = $mergeIds($contentSet->vod_id, $vodSet);
        }

        if ($tvShowSet) {
            $contentSet->tv_show_id = $mergeIds($contentSet->tv_show_id, $tvShowSet);
        }

        $contentSet->featured_row_status = $this->request->input('featured_row_status') ? 1 : 0;
        $contentSet->show_in_live = $this->request->input('show_in_live') ? 1 : 0;

        $contentSet->save();

        return true;
    }


    public function postDeletechannel($id)
    {
        // Log::info('postDeletechannel called', ['id' => $id]);

        if (empty($id)) {
            // Log::error('Invalid ID provided for postDeletechannel', ['id' => $id]);
            return response()->json(['message' => 'Invalid ID.'], 400);
        }

        // Fetch all records having channel_id data
        $plans = $this->_OrgMonetznSubsriptionContentSets->whereNotNull('channel_id')->get();
        // Log::info('Fetched all plans with channel_id data', ['count' => $plans->count()]);

        foreach ($plans as $plan) {
            $channelIds = $plan->channel_id;

            // Decode JSON if stored as string
            if (is_string($channelIds)) {
                $channelIds = json_decode($channelIds, true);
            }

            if (empty($channelIds) || !is_array($channelIds)) {
                // Log::warning('Invalid or empty channel_id for plan', ['plan_id' => $plan->id]);
                continue;
            }

            // Remove the matching channel ID
            $updated = array_filter($channelIds, function ($ch) use ($id) {
                return (string) $ch !== (string) $id;
            });

            // Check if any ID was removed
            if (count($updated) !== count($channelIds)) {
                // Log::info('Deleting channel from plan', [
                //     'plan_id' => $plan->id,
                //     'deleted_channel_id' => $id,
                // ]);

                $plan->channel_id = array_values($updated);
                $plan->save();

                // Log::info('Channel deleted successfully', [
                //     'plan_id' => $plan->id,
                //     'remaining_channels' => $plan->channel_id,
                // ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Channel deleted successfully.',
                    'plan_id' => $plan->id,
                ]);
            }
        }

        // Log::warning('Channel ID not found in any plan', ['id' => $id]);
        return response()->json(['message' => 'Channel ID not found.'], 404);
    }


    public function postDeleteTvshow($id)
    {
        // Log::info('postDeletechannel called', ['id' => $id]);

        if (empty($id)) {
            // Log::error('Invalid ID provided for postDeletechannel', ['id' => $id]);
            return response()->json(['message' => 'Invalid ID.'], 400);
        }

        // Fetch all records having tv_show_id data
        $plans = $this->_OrgMonetznSubsriptionContentSets->whereNotNull('tv_show_id')->get();
        // Log::info('Fetched all plans with tv_show_id data', ['count' => $plans->count()]);

        foreach ($plans as $plan) {
            $tvShowIds = $plan->tv_show_id;

            // Decode JSON if stored as string
            if (is_string($tvShowIds)) {
                $tvShowIds = json_decode($tvShowIds, true);
            }

            if (empty($tvShowIds) || !is_array($tvShowIds)) {
                // Log::warning('Invalid or empty tv_show_id for plan', ['plan_id' => $plan->id]);
                continue;
            }

            // Remove the matching channel ID
            $updated = array_filter($tvShowIds, function ($ch) use ($id) {
                return (string) $ch !== (string) $id;
            });

            // Check if any ID was removed
            if (count($updated) !== count($tvShowIds)) {
                // Log::info('Deleting channel from plan', [
                //     'plan_id' => $plan->id,
                //     'tv_show_id' => $id,
                // ]);

                $plan->tv_show_id = array_values($updated);
                $plan->save();

                // Log::info('Channel deleted successfully', [
                //     'plan_id' => $plan->id,
                //     'remaining_channels' => $plan->tv_show_id,
                // ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Channel deleted successfully.',
                    'plan_id' => $plan->id,
                ]);
            }
        }

        // Log::warning('Channel ID not found in any plan', ['id' => $id]);
        return response()->json(['message' => 'Channel ID not found.'], 404);
    }

    public function postDeleteMovie($id)
    {
        // Log::info('postDeletechannel called', ['id' => $id]);

        if (empty($id)) {
            // Log::error('Invalid ID provided for postDeletechannel', ['id' => $id]);
            return response()->json(['message' => 'Invalid ID.'], 400);
        }

        // Fetch all records having vod_id data
        $plans = $this->_OrgMonetznSubsriptionContentSets->whereNotNull('vod_id')->get();
        // Log::info('Fetched all plans with vod_id data', ['count' => $plans->count()]);

        foreach ($plans as $plan) {
            $vodIds = $plan->vod_id;

            // Decode JSON if stored as string
            if (is_string($vodIds)) {
                $vodIds = json_decode($vodIds, true);
            }

            if (empty($vodIds) || !is_array($vodIds)) {
                // Log::warning('Invalid or empty vod_id for plan', ['plan_id' => $plan->id]);
                continue;
            }

            // Remove the matching channel ID
            $updated = array_filter($vodIds, function ($ch) use ($id) {
                return (string) $ch !== (string) $id;
            });

            // Check if any ID was removed
            if (count($updated) !== count($vodIds)) {
                // Log::info('Deleting channel from plan', [
                //     'plan_id' => $plan->id,
                //     'vod_id' => $id,
                // ]);

                $plan->vod_id = array_values($updated);
                $plan->save();

                // Log::info('Channel deleted successfully', [
                //     'plan_id' => $plan->id,
                //     'remaining_channels' => $plan->vod_id,
                // ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Channel deleted successfully.',
                    'plan_id' => $plan->id,
                ]);
            }
        }

        // Log::warning('Channel ID not found in any plan', ['id' => $id]);
        return response()->json(['message' => 'Channel ID not found.'], 404);
    }

}
