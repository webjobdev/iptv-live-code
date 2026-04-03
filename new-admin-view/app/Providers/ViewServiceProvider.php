<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Contus\Organizations\Model\OrganizationAnnouncement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class ViewServiceProvider extends ServiceProvider {
    public function register(): void {
    }

    // In AppServiceProvider or your custom provider:
    // public function boot(): void {
    //     if (app()->runningInConsole() || request()->is('api/*')) {
    //         return;
    //     }

    //     View::composer('base::layouts.headers.dashboard', function ($view) {
    //         $announcements = collect();
    //         $todayCount = 0;

    //         if (Auth::check()) {
    //             $userId = Auth::id();

    //             $announcements = OrganizationAnnouncement::where('user_id', $userId)
    //                 ->orderBy('created_at', 'desc')
    //                 ->limit(5)
    //                 ->get();

    //             $todayCount = OrganizationAnnouncement::where('user_id', $userId)
    //                 ->whereDate('created_at', \Carbon\Carbon::today())
    //                 ->count();

    //             Log::info('User ID: ' . $userId);
    //             Log::info('Today announcement count: ' . $todayCount);
    //             Log::info('Announcements:', $announcements->toArray());
    //         } else {
    //             // Log::warning('View composer triggered, but user is not authenticated.');
    //         }

    //         $view->with([
    //             'announcements' => $announcements,
    //             'todayAnnouncementCount' => $todayCount,
    //         ]);
    //     });
    // }
}
