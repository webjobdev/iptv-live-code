<?php

namespace Contus\Organizations\Model;

use Contus\Base\Model;

class ChannelListing extends Model
{
    protected $table = 'organization_channel_listing';

    protected $fillable = [
        'organization_id',
        'monitization_plan_id',
        'channel_listing',
        'sequence_assigned_channels',
        'group_channel_list',
    ];

    protected $casts = [
        'sequence_assigned_channels' => 'array',
        'group_channel_list' => 'array'
    ];

    public function GetMonPlan()
    {
        return $this->belongsTo(OrgMonetizationPlanss::class, 'monitization_plan_id', 'id');
    }
}