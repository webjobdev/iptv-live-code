<?php

namespace Contus\Organizations\Model;

use Contus\Base\Model;
use Contus\Channel\Model\Channel;
use Contus\Tvshow\Model\SeasonEpisode;
use Contus\Tvshow\Model\TvShow;
use Contus\Tvshow\Model\TvShowSeason;
use Contus\Video\Models\Video;
use Contus\Vod\Model\VideoOnDemad;

class RowOrder extends Model
{
    protected $table = 'org_appcust_row_order';

    protected $fillable = [
        'organization_id',
        'row_order',
        'horizontal_image',
        'vertical_image',
        'title',
        'description',
        'assigne_row',
        'poster_type',
        'poster_size',
        'gradient',
        'no_set'
    ];

    protected $casts = [
        'assigne_row' => 'array'
    ];

    protected $appends = ['channel_data', 'vod_data', 'tv_show_data', 'liveevent_data'];

    public function getChannelDataAttribute()
    {
        $assigned = collect($this->assigne_row);

        $channel = $assigned
            ->where('row_type', 'channel')
            ->pluck('row_data')
            ->flatten(1)
            ->pluck('id')
            ->unique()
            ->values();

        // Fetch channels from DB
        return Channel::whereIn('id', $channel)->get();
    }

    public function getVodDataAttribute()
    {
        $assigned = collect($this->assigne_row);
        $vod = $assigned
            ->where('row_type', 'vod')
            ->pluck('row_data')
            ->flatten(1)
            ->pluck('id')
            ->unique()
            ->values();
        return VideoOnDemad::whereIn('id', $vod)->get();
    }

    public function getTvShowDataAttribute()
    {
        $assigned = collect($this->assigne_row);
        $tvshow = $assigned
            ->where('row_type', 'tvshow')
            ->pluck('row_data')
            ->flatten(1)
            ->pluck('id')
            ->unique()
            ->values();
        return TvShow::whereIn('id', $tvshow)->get();
    }

    public function getLiveeventDataAttribute()
    {
        $assigned = collect($this->assigne_row);
        $liveevent = $assigned
            ->where('row_type', 'liveevent')
            ->pluck('row_data')
            ->flatten(1)
            ->pluck('id')
            ->unique()
            ->values();
        return Video::whereIn('id', $liveevent)->get();
    }
}