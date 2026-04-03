<?php

namespace Contus\AppApi\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SaveIp
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(Login $event)
    {
        // Log::info("Event ; ", [$event]);
        // Log::info("User ; ", [$event->user]);
        $event->user->ip_address = request()->ip();
        $event->user->save();
    }
}
