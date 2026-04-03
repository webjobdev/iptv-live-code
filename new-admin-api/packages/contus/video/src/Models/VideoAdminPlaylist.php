<?php

/**
 * Playlists Models.
 *
 * @name Playlists
 * @vendor Contus
 * @package Video
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2018 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Models;

use Carbon\Carbon;
use Contus\Video\Models\Playlists;
use Contus\Video\Models\Video;
use Contus\Base\Contracts\AttachableModel;
use Contus\Base\Helpers\StringLiterals;
use Contus\Base\Model;
use Symfony\Component\HttpFoundation\File\File;

class VideoAdminPlaylist extends Model
{

    /**
     * The database table used by the model.
     *
     * @vendor Contus
     *
     * @package Audio
     * @var string
     */
    protected $table = 'video_admin_playlists_tracks';

    protected $hidden = ['creator_id', 'updated_at', 'updator_id'];

}
