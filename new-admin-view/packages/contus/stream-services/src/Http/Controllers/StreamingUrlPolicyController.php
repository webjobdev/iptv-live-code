<?php

namespace Contus\StreamServices\Http\Controllers;

use Contus\Base\Controller;

class StreamingUrlPolicyController extends Controller {

    public function index() {
        return view('stream-services::stream-policy.index');
    }

    public function getGridlist() {
        return view('stream-services::stream-policy.gridView');
    }

    public function addPolicy() {
        return view('stream-services::stream-policy.create');
    }

    public function editPolicy() {
        return view('stream-services::stream-policy.edit');
    }

    public function viewPolicy() {
        return view('stream-services::stream-policy.edit');
    }

}
