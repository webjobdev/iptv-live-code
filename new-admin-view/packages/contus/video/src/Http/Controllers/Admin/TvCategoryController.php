<?php

namespace Contus\Video\Http\Controllers\Admin;

use Contus\Base\Controller;

class TvCategoryController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\View
     */
    public function getIndex() {
        return view('video::admin.categories.tv-category.index');
    }

    /**
     * get Grid template
     *
     * @return \Illuminate\Http\View
     */
    public function getGridlist() {
        return view('video::admin.categories.tv-category.gridView');
    }
}
