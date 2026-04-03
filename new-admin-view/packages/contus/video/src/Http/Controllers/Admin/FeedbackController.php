<?php

namespace Contus\Video\Http\Controllers\Admin;

use Contus\Base\Controller;

class FeedbackController extends Controller {

    public function index() {
        return view('video::admin.feedback.feedback');
    }
}
