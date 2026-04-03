<?php

/**
 * AudioBaseController
 *
 * @name       AudioBaseController
 * @vendor     Contus
 * @package    Audio
 * @version    1.0
 * @author     Contus<developers@contus.in>
 * @copyright  Copyright (C) 2018 Contus. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Playlist\Http\Controllers\Admin;

use Contus\Base\Controller as BaseController;

class AudioBaseController extends BaseController
{
    /**
     * Method to load the index of corresponding module
     * @vendor     Contus
     * @package    Audio
     * @return \Illuminate\Http\View
     */
    public function getIndex($route)
    {        
        // $route = 'ads';
        $response = '';
        switch ($route) {
            case 'album':
                $response = view('audio::admin.albums.index');
                break;
            case 'artists':
                $response = view('audio::admin.artists.index');
                break;
            case 'audios':
                $response = view('audio::admin.audios.index');
                break;
            case 'languages':
                $response = view('audio::admin.languages.index');
                break;
            case 'playlists':
                $response = view('audio::admin.playlists.index');
                break;
            case 'genres':
                $response = view('audio::admin.genres.index');
                break;
            case 'ads':
                $response = view('audio::admin.audioAds.index');
                break;
            default:
                break;
        }
        return $response;
    }
    /**
     * Method to load the grid page of corresponding module
     * @vendor     Contus
     * @package    Audio
     * @return \Illuminate\Http\View
     */
    public function getGridlist($route)
    {
        $response = '';
        switch ($route) {
            case 'albums':
                $response = view('audio::admin.albums.gridView');
                break;
            case 'artists':
                $response = view('audio::admin.artists.gridView');
                break;
            case 'audios':
                $response = view('audio::admin.audios.gridView');
                break;
            case 'languages':
                $response = view('audio::admin.languages.gridView');
                break;
            case 'playlists':
                $response = view('audio::admin.playlists.gridView');
                break;
            case 'genres':
                $response = view('audio::admin.genres.gridView');
                break;
            case 'ads':
                $response = view('audio::admin.audioAds.gridView');
                break;
            default:
                break;
        }
        return $response;
    }
    /**
     * Method to load the add form page of corresponding module
     * @vendor     Contus
     * @package    Audio
     * @return \Illuminate\Http\View
     */
    public function getAdd($route)
    {
        $response = '';
        switch ($route) {
            case 'album':
                $response = view('audio::admin.albums.add');
                break;
            case 'audio':
                $response = view('audio::admin.audios.add');
                break;
            default:
                break;
        }
        return $response;
    }
    /**
     * Method to load the edit form page of corresponding module
     * @vendor     Contus
     * @package    Audio
     * @return \Illuminate\Http\View
     */
    public function getEdit($route, $id)
    {
        $response = '';
        switch ($route) {
            case 'albums':
                $response = view('audio::admin.albums.edit', ['id' => $id]);
                break;
            default:
                break;
        }
        return $response;
    }
    /**
     * get Grid template
     *
     * @return \Illuminate\Http\View
     */
    public function getDetailsAudioEdit($id)
    {
        return view('audio::admin.audios.edit', [
            'id' => $id,
        ]);
    }

    /**
     * Controller function to get the album related audios.
     *
     * @param integer $id The id of the album.
     * @return Ambigous <\Contus\Base\response, \Illuminate\Http\JsonResponse>
     */
    public function getAlbumAudios($id)
    {
        return view('audio::admin.albums.audios', [
            'id' => $id,
        ]);
    }

    /**
     * Show Audio Details in to audio details page
     *
     * @return \Illuminate\Http\View
     */
    public function getViewDetailsAudio($id)
    {
        $redirectViewAudioDetail = 'audio::admin.audios.viewDetailAudio';
        return view($redirectViewAudioDetail, [
            'id' => $id,
        ]);
    }
}
