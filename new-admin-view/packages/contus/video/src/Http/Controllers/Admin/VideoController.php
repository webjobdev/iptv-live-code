<?php

/**
 * VideoController
 *
 * @name       VideoController
 * @vendor     Contus
 * @package    Video
 * @version    1.0
 * @author     Contus<developers@contus.in>
 * @copyright  Copyright (C) 2016 Contus. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */

namespace Contus\Video\Http\Controllers\Admin;

use Contus\Base\Controller as BaseController;
use Contus\Video\Repositories\VideoRepository;
use Contus\Video\Repositories\CollectionRepository;
use Contus\Video\Repositories\CategoryRepository;
use Contus\Base\Helpers\StringLiterals;
use Illuminate\Http\Request;

class VideoController extends BaseController
{

  public $videoRepository;
  /**
   * class property is used to initiate the class
   *
   * @vendor Contus
   *
   * @package Video
   * @var array
   */
  public function __construct(VideoRepository $videoRepository, CollectionRepository $collectionRepository, CategoryRepository $categoryRepository)
  {
    $this->videoRepository = $videoRepository;
    $this->collectionRepository = $collectionRepository;
    $this->categoryRepository = $categoryRepository;
  }

  /**
   * Show the videos page
   *
   * @vendor Contus
   *
   * @package Video
   * @return \Illuminate\Http\View
   */
  public function getIndex(Request $request)
  {

    if ($request->segment(2) == 'livevideos') {

      $view = 'video::admin.livevideos.index';
      $page = 'live';
    } else if ($request->segment(2) == 'radio') {
      $view = 'video::admin.radio.index';
      $page = 'radio';
    } else if ($request->segment(2) == 'liveevents') {
      $view = 'video::admin.liveevents.index';
      $page = 'liveevents';
    } else {
      $view = 'video::admin.videos.index';
      $page = 'vod';
    }
    return view($view, ['page' => $page]);
  }
  /**
   * get Grid template
   *
   * @return \Illuminate\Http\View
   */
  public function getGridlist(Request $request)
  {
    $page = ($request->segment(2) == 'livevideos') ? 'live' : 'vod';
    return view('video::admin.videos.gridView', ['page' => $page]);
  }

  public function getRadioGridlist(Request $request)
  {
    $page = ($request->segment(2) == 'radio') ? 'radio' : 'vod';
    return view('video::admin.radio.gridView', ['page' => $page]);
  }

  public function getLiveeventsGridlist(Request $request)
  {
    $page = ($request->segment(2) == 'liveevents') ? 'liveevents' : 'vod';
    return view('video::admin.liveevents.gridView', ['page' => $page]);
  }

  /**
   * get wowza from template
   *
   * @return \Illuminate\Http\View
   */
  public function getAdd()
  {
    return view('video::admin.videos.live');
  }

  public function radioAdd()
  {
    return view('video::admin.videos.radio');
  }


  public function liveeventsAdd()
  {
    return view('video::admin.videos.liveevents');
  }

  /**
   * get wowza from template
   *
   * @return \Illuminate\Http\View
   */
  public function postAdd()
  {
  }
  /**
   * get Detail video view and edit template
   *
   * @return \Illuminate\Http\View
   */
  public function getDetailsVideoEdit($id)
  {
    // return view ( 'video::admin.videos.detailVideo' ,[ 
    return view('video::admin.videos.edit', [
      'id' => $id,
      'region' => env('AWS_REGION'),
      'key' => env('AWS_KEY'),
      'secret' => env('AWS_SECRET'),
      's3_bucket' => env('AWS_BUCKET'),
      'developer_key' => config()->get('settings.google.google-general.developer_key'),
      'client_id' => config()->get('settings.google.google-general.client_id'),
    ]);
  }


  public function getLiveVideoEdit($id)
  {
    return view('video::admin.videos.liveedit', [
      'id' => $id,
      'region' => env('AWS_REGION'),
      'key' => env('AWS_KEY'),
      'secret' => env('AWS_SECRET'),
      's3_bucket' => env('AWS_BUCKET'),
      'developer_key' => config()->get('settings.google.google-general.developer_key'),
      'client_id' => config()->get('settings.google.google-general.client_id'),
    ]);
  }

  public function getDetailsRadioEdit($id)
  {
    // return view ( 'video::admin.videos.detailVideo' ,[ 
    return view('video::admin.videos.radioedit', [
      'id' => $id,
      'region' => env('AWS_REGION'),
      'key' => env('AWS_KEY'),
      'secret' => env('AWS_SECRET'),
      's3_bucket' => env('AWS_BUCKET'),
      'developer_key' => config()->get('settings.google.google-general.developer_key'),
      'client_id' => config()->get('settings.google.google-general.client_id'),
    ]);
  }

  public function getDetailsLiveeventsEdit($id)
  {
    // return view ( 'video::admin.videos.detailVideo' ,[ 
    return view('video::admin.videos.liveeventsedit', [
      'id' => $id,
      'region' => env('AWS_REGION'),
      'key' => env('AWS_KEY'),
      'secret' => env('AWS_SECRET'),
      's3_bucket' => env('AWS_BUCKET'),
      'developer_key' => config()->get('settings.google.google-general.developer_key'),
      'client_id' => config()->get('settings.google.google-general.client_id'),
    ]);
  }


  /**
   * Show Video Details in to video details page
   *
   * @return \Illuminate\Http\View
   */
  public function getViewDetailsVideo($id)
  {

    $redirectViewVideoDetail = 'video::admin.videos.viewDetailVideo';
    if (isset($_REQUEST['collectionId'])) {
      $this->breadcrumb = $this->collectionRepository->getCollection($_REQUEST['collectionId']);
      return view($redirectViewVideoDetail, [
        'id' => $id,
        'collection_title' => $this->breadcrumb->title
      ]);
    } else if (isset($_REQUEST[StringLiterals::VIDEOCATEGORYID])) {
      $this->breadcrumb = $this->categoryRepository->getCategory($_REQUEST[StringLiterals::VIDEOCATEGORYID]);
      return view($redirectViewVideoDetail, [
        'id' => $id,
        'category_title' => $this->breadcrumb->title,
        StringLiterals::VIDEOCATEGORYID => $_REQUEST[StringLiterals::VIDEOCATEGORYID],
      ]);
    } else {
      return view($redirectViewVideoDetail, [
        'id' => $id,
      ]);
    }
  }
  /**
   * get Detail video language view and edit template
   *
   * @return \Illuminate\Http\View
   */
  public function getDetailsVideoLanguageEdit($id, Request $request)
  {
  }

  /**
   * upload video page
   */
  public function getVideoUpload(Request $request)
  {
    // return $request;
    $webseries_data = array();
    if (request('is_webseries') == 'true') {
      $webseries_data = array(
        'webseries_id' => request('webseries_id'),
        'webseries_genre' => request('webseries_genre')
      );
    }
    return view('video::admin.videos.add', [
      'region' => env('AWS_REGION'),
      'key' => env('AWS_KEY'),
      'secret' => env('AWS_SECRET'),
      's3_bucket' => env('AWS_BUCKET'),
      'developer_key' => config()->get('settings.google.google-general.developer_key'),
      'client_id' => config()->get('settings.google.google-general.client_id'),
      'webseries_data' => $webseries_data
      // dd($webseries_data)
    ]);
  }

  public function store()
  {
    return view('video::admin.videos.demo.add');
  }

  public function togglePublishNow(Request $request, $id)
  {
    if ($request->has('is_active')) {
      $status = $this->videoRepository->video->where('id', $id)->update(['is_active' => $request->is_active]);
      return response()->json(['message' => 'Monetization Plan Update.', 'status' => 'success']);
    }
    return response()->json(['message' => 'Invalid Request', 'status' => 'error'], 400);
  }
}
