<?php

namespace Contus\AppApi\Model;

use Contus\Base\Model;
use Contus\Organizations\Model\Organization;
use Contus\Organizations\Model\OrgSubscribers;
use Contus\Tvshow\Model\TvShow;
use Contus\Vod\Model\VideoOnDemad;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubscriberLike extends Model
{

    use HasFactory;

    protected $table = 'subscriber_like';
    protected $fillable = ['subscriber_id', 'movie_id', 'series_id', 'channel_id'];

    public function orgSubscriber()
    {
        return $this->belongsTo(OrgSubscribers::class, 'subscriber_id', 'id');
    }

    public function movie()
    {
        return $this->belongsTo(VideoOnDemad::class, 'movie_id', 'id');
    }

    public function series()
    {
        return $this->belongsTo(TvShow::class, 'series_id', 'id');
    }
}
