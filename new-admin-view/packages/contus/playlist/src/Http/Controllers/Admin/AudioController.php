<?php

/**
 * Audio Controller
 *
 * To manage the Audios such as create, edit and delete
 *
 * @name       Audio Controller
 * @version    1.0
 * @author     Contus Team <developers@contus.in>
 * @copyright  Copyright (C) 2019 Contus. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Playlist\Http\Controllers\Admin;

use Contus\Playlist\Repositories\AudioRepository;
use Contus\Base\Controller as BaseController;
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon;

class AudioController extends BaseController
{
  /**
   * Construct method
   */
  public function __construct(AudioRepository $audioRepository)
  {
    parent::__construct();
    $this->_audioRepository = $audioRepository;
    $this->_audioRepository->setRequestType(static::REQUEST_TYPE);
  }

  /**
   * get details audio edit template
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
