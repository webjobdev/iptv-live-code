<?php

/**
 * AudioTrait
 *
 * To manage the functionalities related to the Videos module from Video Controller
 *
 * @vendor Contus
 *
 * @package Video
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Playlist\Traits;

use Contus\Playlist\Models\Artist;
use Contus\Playlist\Models\Albums;

trait AudioTrait
{
    function getArtistName() {
        $artistName = '';
        $artist = app()->request->audio_artist_id;
        if(!empty($artist)) {
            $artistInfo = Artist::find($artist);
            if(!empty($artistInfo)) {
                $artistName = $artistInfo->artist_name;
            }
        }
        else {
            $artist = $this->artist()->first();
            if (!empty($artist)) {
                $artistName = $artist->artist_name;
            }
        }

        return $artistName;
    }

    function getAlbumName() {
        $albumName = '';
        $album = app()->request->album_id;
        if(!empty($album)) {
            $albumInfo = Albums::find($album);
            if(!empty($albumInfo)) {
                $albumName = $albumInfo->album_name;
            }
        }
        else {
            $album = $this->artist()->first();
            if (!empty($album)) {
                $albumName = $album->album_name;
            }
        }

        return $albumName;
    }
}
