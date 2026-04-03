<?php

/**
 * Webseries Controller
 *
 * To manage the Webseries such as create, edit and delete
 *
 * @name       Webseries Controller
 * @version    1.0
 * @author     Contus Team <developers@contus.in>
 * @copyright  Copyright (C) 2016 Contus. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Http\Controllers\Admin;

use Contus\Base\Controller as BaseController;
use Contus\Video\Repositories\CategoryRepository;

class WebseriesController extends BaseController
{
    /**
     * Construct method
     */
    public function __construct(CategoryRepository $categoryRepository)
    {
        parent::__construct();
        $this->_categoryRepository = $categoryRepository;
        $this->_categoryRepository->setRequestType(static::REQUEST_TYPE);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\View
     */
    public function getIndex()
    {
        return view('video::admin.webseries.index');
    }

    public function getAdd()
    {
        return view('video::admin.webseries.add');
    }

    public function getEdit($id)
    {
        $category = $this->_categoryRepository->getCategoryFromWebSeries($id);
        return view('video::admin.webseries.edit', 
            [
                'id' => $id,
                'category' => $category,
            ]);
    }
    /**
     * get Grid template
     *
     * @return \Illuminate\Http\View
     */
    public function getGridlist()
    {
        return view('video::admin.webseries.gridView');
    }
    /**
     * Function to get list of webseries with their hierarchy.
     */
    public function getCategoryList()
    {
        return $this->_categoryRepository->getAllCategoryList();
    }
    /**
     * Controller function to get the webseries related videos.
     *
     * @param integer $id The id of the webseries.
     * @return Ambigous <\Contus\Base\response, \Illuminate\Http\JsonResponse>
     */
    public function getVideos($id)
    {
        return view('video::admin.webseries.videos', [
            'id' => $id,
        ]);
    }
}
