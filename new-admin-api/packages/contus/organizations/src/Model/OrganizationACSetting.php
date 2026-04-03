<?php

namespace Contus\Organizations\Model;

use Contus\Channel\Model\Channel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class OrganizationACSetting extends Model
{
    protected $table = 'organization_setting';

    protected $fillable = [
        'organization_id',
        'time_zone',
        'system_default',
        'pin_code',
        'random',
        'screen_server',
        'minutes',
        'ss_system_default',
        'stb_start_channel',
        'channel_id',
        'sorting_number',
    ];

    public function GetChannel()
    {
        return $this->belongsTo(Channel::class, 'channel_id', 'id');
    }
}