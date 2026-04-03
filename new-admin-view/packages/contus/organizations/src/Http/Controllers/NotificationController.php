<?php

namespace Contus\Organizations\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\OrganizationAnnouncement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller {
    // public function morenotification(Request $request){
    //     $user = auth()->user();
    //     $announcements = OrganizationAnnouncement::where('user_id', Auth::id())
    //         ->orderBy('created_at', 'desc')
    //         ->get();
    //     return view('organization.notification', compact('announcements'));
    // }

    public function showdetails() {
        return view('organizations::notification.index');
    }

    public function getGridlist() {
        return view('organizations::notification.gridView');
    }
}
