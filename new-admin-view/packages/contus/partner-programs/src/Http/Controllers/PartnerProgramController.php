<?php

namespace Contus\PartnerProgram\Http\Controllers;

use App\Http\Controllers\Controller;
use Contus\PartnerProgram\Models\PartnerProgram;
use Illuminate\Http\Request;

class PartnerProgramController extends Controller {

    public function index() {
        return view('partner-programs::index');
    }

    public function getGridlist() {
        return view('partner-programs::gridView');
    }

    public function addPartnerProgram() {
        return view('partner-programs::create');
    }

    public function editPartnerProgram() {
        return view('partner-programs::edit');
    }

}
