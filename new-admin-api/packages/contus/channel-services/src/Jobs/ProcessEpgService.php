<?php

namespace Contus\ChannelServices\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Contus\ChannelServices\Model\EpgService;
use Contus\ChannelServices\Services\EpgParserService;

class ProcessEpgService implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The EpgService instance.
     *
     * @var EpgService
     */
    protected $epgService;

    /**
     * Create a new job instance.
     *
     * @param EpgService $epgService
     * @return void
     */
    public function __construct(EpgService $epgService)
    {
        $this->epgService = $epgService;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $parser = new EpgParserService();
        $parser->parse($this->epgService, 'Cron/Job');
    }
}
