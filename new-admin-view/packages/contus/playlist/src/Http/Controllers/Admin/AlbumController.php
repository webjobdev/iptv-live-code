<?php

/**
 * Album Controller
 *
 * To manage the Album such as create, edit and delete
 *
 * @name       Album Controller
 * @version    1.0
 * @author     Contus Team <developers@contus.in>
 * @copyright  Copyright (C) 2019 Contus. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Playlist\Http\Controllers\Admin;

use Contus\Playlist\Repositories\AlbumRepository;
use Contus\Base\Controller as BaseController;
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon;

class AlbumController extends BaseController
{
  /**
   * Construct method
   */
  public function __construct(AlbumRepository $albumRepository)
  {
    parent::__construct();
    $this->_albumRepository = $albumRepository;
    $this->_albumRepository->setRequestType(static::REQUEST_TYPE);
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
      'id' => $id
    ]);
  }
}
