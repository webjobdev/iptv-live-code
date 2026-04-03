<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Contus\Subscribers\Model\OrgSubscriberAndPayment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SubscriberSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:subscriber-subscription';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Promote queued subscriptions to active when current subscriptions expire';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }


    public function handle()
    {
        $now = Carbon::now();
        Log::info('⏰ Checking for subscriptions to promote at ' . $now->toDateTimeString());

        // Find all expired active subscriptions
        $expired = OrgSubscriberAndPayment::where('is_active', 1)
            ->where('end_date', '<', $now)
            ->get();

        foreach ($expired as $activeSub) {
            Log::info("🔒 Expiring subscription ID {$activeSub->id}");
            // Mark as inactive
            $activeSub->is_active = 0;
            $activeSub->save();

            // Promote queued subscription (first in line for same subscriber)
            $queuedSub = OrgSubscriberAndPayment::where('subscriber_id', $activeSub->subscriber_id)
                ->where('is_active', 2)
                ->orderBy('start_date', 'asc')
                ->first();

            if ($queuedSub) {
                Log::info("✅ Promoting queued subscription ID {$queuedSub->id}");
                $queuedSub->is_active = 1;
                $queuedSub->save();
            } else {
                Log::info("⚠️ No queued subscription found for subscriber {$activeSub->subscriber_id}");
            }
        }

        Log::info('🎉 Promotion process complete.');
    }
    /**
     * Execute the console command.
     *
     * @return int
     */
    // public function handle()
    // {
    //     return 0;
    // }
}
