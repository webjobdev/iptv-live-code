<?php

namespace Contus\Organizations\Model;

use App\Models\User;
use Contus\Base\Model;
use Contus\Tvshow\Model\SeasonEpisode;
use Contus\Tvshow\Model\TvShow;
use Contus\Tvshow\Model\TvShowSeason;
use Illuminate\Support\Facades\Log;

class TvShowContent extends Model
{

    protected $table = 'tvshow_content_set';

    protected $fillable = [
        'organization_id',
        'name',
        'item_type',
        'assigned_tv_show',
        'assigned_tv_show_season',
        'assigned_tv_show_episode',
        'currency',

        // Buy monetization
        'monitization_type_buy',
        'payment_method_buy',
        'buy_price',

        // Rent monetization
        'monitization_type_rent',
        'payment_method_rent',
        'rent_price',
        'period',
        'period_type',

        'cover_image',
        'description',
        'by_user',
        'is_active',
    ];

    public function getorg()
    {
        $org = $this->belongsTo(OrganizationDetail::class, 'organization_id', 'id');
        return $org;
    }

    public function getuser()
    {
        $user = $this->belongsTo(User::class, 'by_user', 'id');
        return $user;
    }

    protected $casts = [
        'assigned_tv_show' => 'array',
        'assigned_tv_show_season' => 'array',
        'assigned_tv_show_episode' => 'array',
    ];

    protected $appends = ['tv_shows', 'tv_show_seasons', 'tv_show_episodes'];

    function getTvShowsAttribute()
    {
        if (empty($this->assigned_tv_show)) {
            return collect();
        }

        $tvShows = is_string($this->assigned_tv_show) ? json_decode($this->assigned_tv_show, true) : $this->assigned_tv_show;

        if (empty($tvShows)) {
            return collect();
        }

        $tvShowsIds = collect($tvShows)->pluck('id');
        return TvShow::whereIn('id', $tvShowsIds)->get();
    }

    function getTvShowSeasonsAttribute()
    {
        if (empty($this->assigned_tv_show_season)) {
            return collect();
        }

        $seasons = is_string($this->assigned_tv_show_season) ? json_decode($this->assigned_tv_show_season, true) : $this->assigned_tv_show_season;

        if (empty($seasons) || !isset($seasons[0]['get_season_data'])) {
            return collect();
        }

        $seasonIds = collect($seasons[0]['get_season_data'])->pluck('id');
        return TvShowSeason::whereIn('id', $seasonIds)->get();
    }

    function getTvShowEpisodesAttribute()
    {
        if (empty($this->assigned_tv_show_episode)) {
            return collect();
        }

        $episodes = is_string($this->assigned_tv_show_episode) ? json_decode($this->assigned_tv_show_episode, true) : $this->assigned_tv_show_episode;

        if (empty($episodes) || !isset($episodes[0]['get_seasons'][0]['get_episodes'])) {
            return collect();
        }

        $episodeIds = collect($episodes[0]['get_seasons'][0]['get_episodes'])->pluck('id');
        return SeasonEpisode::whereIn('id', $episodeIds)->get();
    }
}
