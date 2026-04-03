<?php

namespace Contus\Video\Models;

use Contus\Base\Model;


class PlaylistTranslation extends Model
{
     /**
     * The attributes that are mass assignable.
     *
     * @vendor Contus
     *
     * @package Audio
     * @var array
     */
    protected $fillable = ['name', 'playlist_id', 'language_id'];

}
