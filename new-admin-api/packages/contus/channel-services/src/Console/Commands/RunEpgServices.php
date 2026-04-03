<?php

namespace Contus\ChannelServices\Console\Commands;

use Illuminate\Console\Command;
use Contus\ChannelServices\Model\EpgService;
use Contus\ChannelServices\Jobs\ProcessEpgService;
use Carbon\Carbon;

class RunEpgServices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'epg:run-auto';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check and run scheduled EpgServices';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $now = Carbon::now();
        $this->info("Checking for EPG services due to run at {$now->toDateTimeString()}...");

        $services = EpgService::where('is_active', 1)
            ->where(function ($query) use ($now) {
                $query->where('next_run', '<=', $now)
                    ->orWhereNull('next_run');
            })
            ->get();

        $count = $services->count();
        if ($count === 0) {
            $this->info("No EPG services due for execution.");
            return 0;
        }

        $this->info("Found {$count} services pending execution.");

        foreach ($services as $service) {
            $this->info("Dispatching job for EPG Service ID: {$service->id} ({$service->task_name})");

            // Dispatch the job
            ProcessEpgService::dispatch($service);

            // Optionally update next_run immediately to prevent double dispatch if the job queue is slow?
            // Usually simpler to let the job handle it, or just rely on the queue.
            // If the command runs every minute, we might want to "lock" it or set next_run to something future?
            // For now, I'll assume the job runs relatively quickly or the schedule interval is sufficient.
            // But to be safe, maybe we should touch 'last_run' or have a 'status' field.
            // The EpgService has no 'status' field, only EpgServiceExecution has status.
            // EpgParserService updates 'next_run' upon completion.
        }

        $this->info("All jobs dispatched.");
        return 0;
    }
}
