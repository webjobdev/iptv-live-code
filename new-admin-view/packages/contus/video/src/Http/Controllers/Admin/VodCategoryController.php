<?php

namespace Contus\Video\Http\Controllers\Admin;

use Contus\Base\Controller;

class VodCategoryController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\View
     */
    public function getIndex() {
        return view('video::admin.categories.vod-category.index');
    }

    /**
     * get Grid template
     *
     * @return \Illuminate\Http\View
     */
    public function getGridlist() {
        return view('video::admin.categories.vod-category.gridView');
    }
}
