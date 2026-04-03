<?php

namespace Contus\AppApi\Model;

use Contus\Base\Model;
use Contus\Subscribers\Model\OrgSubscribers;
use Contus\Tvshow\Model\SeasonEpisode;
use Contus\Tvshow\Model\TvShow;
use Contus\Vod\Model\VideoOnDemad;

class ContinueWatching extends Model
{

    protected $table = 'continue_watchings';

    protected $fillable = [
        'subscriber_id',
        'watching_type',
        'watchable_id',
        'watched_duration',
        'total_duration',
        'is_completed'
    ];

    public function orgSubscriber()
    {
        return $this->belongsTo(OrgSubscribers::class, 'subscriber_id', 'id');
    }

    public function movie()
    {
        return $this->belongsTo(VideoOnDemad::class, 'watchable_id', 'id');
    }

    public function Episode()
    {
        return $this->belongsTo(SeasonEpisode::class, 'watchable_id', 'id');
    }

    public function GetTvShow()
    {
        return $this->belongsTo(TvShow::class, 'id');
    }
}