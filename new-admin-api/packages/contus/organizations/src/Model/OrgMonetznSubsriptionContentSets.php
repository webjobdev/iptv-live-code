<?php

namespace Contus\Organizations\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrgMonetznSubsriptionContentSets extends Model
{
    use HasFactory;

    protected $table = 'monetzn_subscription_content_sets';

    protected $fillable = [
        'organization_id',
        'montzn_plan_id',
        'featured_title',
        'channel_id',
        'channel_add_ons_id',
        'vod_id',
        'vod_add_ons_id',
        'live_event_id',
        'live_event_add_ons_id',
        'tv_show_id',
        'tv_show_add_ons_id',
    ];

    protected $casts = [
        'channel_id' => 'array',
        'channel_add_ons_id' => 'array',
        'vod_id' => 'array',
        'vod_add_ons_id' => 'array',
        'live_event_id' => 'array',
        'live_event_add_ons_id' => 'array',
        'tv_show_id' => 'array',
        'tv_show_add_ons_id' => 'array',
    ];

    protected $appends = ['channels', 'channelAddOns', 'vods', 'vodAddOns', 'tvShows', 'tvShowAddOns', 'lEvents', 'lEventAddOns'];

    public function organization()
    {
        return $this->belongsTo(OrganizationDetail::class, 'organization_id', 'id');
    }

    public function subscription()
    {
        return $this->belongsTo(OrgMonetizationPlanss::class, 'montzn_plan_id', 'id');
    }

    function getChannelsAttribute()
    {
        $channelIds = $this->channel_id;

        if (empty($channelIds)) {
            return collect();
        }

        return ChannelContet::with('getuser')
            ->whereIn('id', $channelIds)
            ->get();
        // return ChannelContet::with('getuser')->whereIn('id', $this->channel_id)->get();
    }

    function getChannelAddonsAttribute()
    {
        // $channelAddOnsIds = json_decode($this->channel_add_ons_id, true);
        $channelAddOnsIds = $this->channel_add_ons_id;

        if (empty($channelAddOnsIds)) {
            return collect();
        }

        return ChannelContet::with('getuser')
            ->whereIn('id', $channelAddOnsIds)
            ->get();
        // return ChannelContet::with('getuser')->whereIn('id', $this->channel_add_ons_id)->get();
    }

    function getVodsAttribute()
    {
        // $vodIds = json_decode($this->vod_id, true);
        $vodIds = $this->vod_id;

        if (empty($vodIds)) {
            return collect();
        }

        return VodContent::with('getuser')
            ->whereIn('id', $vodIds)
            ->get();
        // return VodContent::with('getuser')->whereIn('id', $this->vod_id)->get();
    }

    function getVodAddOnsAttribute()
    {
        // $vodAddOnsIds = json_decode($this->vod_add_ons_id, true);
        $vodAddOnsIds = $this->vod_add_ons_id;

        if (empty($vodAddOnsIds)) {
            return collect();
        }

        return VodContent::with('getuser')
            ->whereIn('id', $vodAddOnsIds)
            ->get();
    }

    function getLEventsAttribute()
    {
        $liveEventIds = $this->live_event_id;

        if (empty($liveEventIds)) {
            return collect();
        }

        return LiveEventContent::with('getuser')
            ->whereIn('id', $liveEventIds)
            ->get();
        // return LiveEventContent::with('getuser')->whereIn('id', $this->live_event_id)->get();
    }

    function getLEventAddOnsAttribute()
    {
        $liveEventAddOnsIds = $this->live_event_add_ons_id;

        if (empty($liveEventAddOnsIds)) {
            return collect();
        }

        return LiveEventContent::with('getuser')
            ->whereIn('id', $liveEventAddOnsIds)
            ->get();
    }

    function getTvShowsAttribute()
    {
        $tvShowIds = $this->tv_show_id;

        if (empty($tvShowIds)) {
            return collect();
        }

        return TvShowContent::with('getuser')
            ->whereIn('id', $tvShowIds)
            ->get();
        // return TvShowContent::with('getuser')->whereIn('id', $this->tv_show_id)->get();
    }

    function getTvShowAddOnsAttribute()
    {
        $tvShowAddOnsIds = $this->tv_show_add_ons_id;

        if (empty($tvShowAddOnsIds)) {
            return collect();
        }

        return TvShowContent::with('getuser')
            ->whereIn('id', $tvShowAddOnsIds)
            ->get();
    }
}
