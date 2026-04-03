<?php

/**
 * Artists Controller
 *
 * To manage the Artists such as create, edit and delete
 *
 * @name       Artists Controller
 * @version    1.0
 * @author     Contus Team <developers@contus.in>
 * @copyright  Copyright (C) 2018 Contus. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Playlist\Http\Controllers\Admin;

use Contus\Playlist\Repositories\ArtistRepository;
use Contus\Base\Controller as BaseController;
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon;

class ArtistController extends BaseController
{
  /**
   * Construct method
   */
  public function __construct(ArtistRepository $artistRepository)
  {
    parent::__construct();
    $this->_artistRepository = $artistRepository;
    $this->_artistRepository->setRequestType(static::REQUEST_TYPE);
  }

  /**
   * Controller function to get the artist related audios.
   *
   * @param integer $id The id of the album.
   * @return Ambigous <\Contus\Base\response, \Illuminate\Http\JsonResponse>
   */
  public function getAritstAudios($id)
  {
    return view('audio::admin.artists.audios', [
      'id' => $id
    ]);
  }

  /**
   * Function to get list of artists with their hierarchy.
   */
  public function getArtistList()
  {
    return $this->_artistRepository->getAllArtistList();
  }

}
