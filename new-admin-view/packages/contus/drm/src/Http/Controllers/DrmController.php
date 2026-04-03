<?php

namespace Contus\Drm\Http\Controllers;

use Contus\Base\Controller;
use Contus\Drm\Model\Drm;
use Contus\Drm\Model\DrmDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DrmController extends Controller {

    public function index() {
        return view('drm::index');
    }

    public function getGridlist() {
        return view('drm::gridView');
    }

    public function destroy(Request $request) {
        $drmId = $request->input('id');
        $deleted = Drm::where('id', $drmId)->delete();
        DrmDetails::where('drm_id', $drmId)->delete();

        return response()->json([
            'success' => $deleted,
            'message' => $deleted ? 'Drm deleted' : 'Drm not found',
        ]);
    }
}
