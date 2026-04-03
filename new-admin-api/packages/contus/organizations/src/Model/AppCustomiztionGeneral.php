<?php

namespace Contus\Organizations\Model;

use Contus\Base\Model;

class AppCustomiztionGeneral extends Model
{
    protected $table = 'organization_general';

    protected $fillable = [
        'organiztion_id',
        'thumbnail_image',
        'live',
        'epg',
        'catchup',
        'movie',
        'sereis',
        'event',
    ];
}
