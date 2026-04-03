<?php

namespace Contus\Organizations\Model;

use Contus\Base\Model;
use Contus\Channel\Model\Channel;
use Contus\User\Models\User;

class LiveEventContent extends Model
{

    protected $table = 'live_event_content_set';

    protected $fillable = [
        'organization_id',
        'name',
        'description',
        'is_active',
        'monitization_type',
        'payment_method',
        'price',
        'currency',
        'assigned_channels',
        'cover_image',
        'by_user',
    ];

    public function getorg()
    {
        $org = $this->belongsTo(Organization::class, 'organization_id', 'id');
        return $org;
    }

    public function getuser()
    {
        $user = $this->belongsTo(User::class, 'by_user', 'id');
        return $user;
    }

    protected $casts = [
        'assigned_channels' => 'array',
    ];

    protected $appends = ['live_event_channels'];

    function getLiveEventChannelsAttribute()
    {
        $channels = json_decode($this->assigned_channels, true);
        $chanlIds = collect($channels)->pluck('id');
        return Channel::whereIn('id', $chanlIds)->get();
    }
}
