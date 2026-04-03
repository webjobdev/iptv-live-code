<?php

/**
 * AudioBaseRepository
 *
 * To manage the audio management such as create, edit and delete
 *
 * @name AudioBaseRepository
 * @version 1.0
 * @author Contus Team <developers@contus.in>
 * @copyright Copyright (C) 2018 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Playlist\Repositories;

use Contus\Playlist\Models\Artist;
use Contus\Playlist\Models\AudioGenres;
use Contus\Playlist\Models\AudioLanguageCategory;
use Contus\Base\Repository as BaseRepository;

class AudioBaseRepository extends BaseRepository
{
    /**
     * Class construct method initialization
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get all Audio Artist details
     *
     * @vendor Contus
     * @return array
     */
    public function getAllAudioArtists(){
        return Artist::select('id', 'artist_name')->where('is_active', 1)->OrderByLatest()->get()->toArray();
    }

    /**
     * Get all Audio Language Category details
     *
     * @vendor Contus
     * @return array
     */
    public function getAllAudioLanguageCategory(){
        return AudioLanguageCategory::select('id', 'language_name')->where('is_active', 1)->OrderByLatest()->get()->toArray();
    }

    /**
     * Get all Audio Genre details
     *
     * @vendor Contus
     * @return array
     */
    public function getAllAudioGenres(){
        return AudioGenres::select('id', 'genre_name')->where('is_active', 1)->OrderByLatest()->get()->toArray();
    }
}
