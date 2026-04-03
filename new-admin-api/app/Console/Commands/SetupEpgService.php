<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Contus\ChannelServices\Model\EpgProgram;
use Contus\ChannelServices\Model\EpgService;
use Contus\ChannelServices\Model\EpgServiceExecution;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class SetupEpgService extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'channel:epg-service {epgServiceId?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup Epg Service';

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
    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle($epgServiceId = null, $executedBy = 'System')
    {
        $epgServiceId = $epgServiceId ?: $this->argument('epgServiceId');

        $parser = new \Contus\ChannelServices\Services\EpgParserService();

        if ($epgServiceId) {
            // Run for specific ID
            $epgService = ($epgServiceId instanceof EpgService) ? $epgServiceId : EpgService::find($epgServiceId);

            if (!$epgService) {
                $this->error("EPG Service ID not found: $epgServiceId");
                return 1;
            }

            $this->info("Starting EPG Parser for: {$epgService->task_name}");
            $result = $parser->parse($epgService, $executedBy);

            if ($result) {
                $this->info("EPG Parsed successfully.");
                return 0;
            } else {
                $this->error("EPG Parsing failed.");
                return 1;
            }

        } else {
            // Run for ALL Active Services
            $this->info("Running EPG Parser for ALL active services...");

            $services = EpgService::where('is_active', 1)->get(); // Assuming 1 is active

            foreach ($services as $service) {
                $this->info("Processing: {$service->task_name} (ID: {$service->id})");
                try {
                    $parser->parse($service, $executedBy);
                } catch (\Exception $e) {
                    $this->error("Error processing service {$service->id}: " . $e->getMessage());
                }
            }

            $this->info("All services processed.");
            return 0;
        }
    }
}
