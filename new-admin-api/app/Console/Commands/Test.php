<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Contus\ChannelServices\Model\EpgProgram;
use Contus\ChannelServices\Model\EpgService;
use Contus\ChannelServices\Model\EpgServiceExecution;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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

        $epgService = EpgService::find(2);
        if (!$epgService) {
            throw new \Exception('EPG Service not found');
        }

        $response = Http::withOptions([
            'timeout' => 120,
            'connect_timeout' => 10,
        ])->withHeaders([
            'Accept' => 'application/xml',
        ])->get($epgService->source_url);

        $epgServiceExecutions = new EpgServiceExecution();
        $epgServiceExecutions->epg_service_id = $epgService->id;
        $epgServiceExecutions->status = "0";
        $epgServiceExecutions->executed_by = "ADMIN";
        $epgServiceExecutions->save();

        if ($response->failed()) {
            $epgServiceExecutions->update([
                "status" => "0",
                "fail_reason" => "Unable to fetch M3U file"
            ]);
            throw new Exception('Unable to fetch M3U file');
        }

        $m3uContent = $response->body();

        // Split lines safely (handles \r\n also)
        $lines = preg_split("/\r\n|\n|\r/", $m3uContent);

        // Get only .xml URLs
        $xmlUrls = array_values(array_filter($lines, function ($line) {
            $line = trim($line);
            return !empty($line)
                && !str_starts_with($line, '#')
                && str_ends_with(strtolower($line), '.xml');
        }));

        if (empty($xmlUrls)) {
            $epgServiceExecutions->update([
                "status" => "0",
                "fail_reason" => "No XML URL found"
            ]);
            throw new Exception('No XML URL found');
        }

        // ✅ THIS is the correct URL to request
        $xmlUrl = trim($xmlUrls[0]);

        $xmlResponse = Http::get($xmlUrl);
        if ($xmlResponse->failed()) {
            $epgServiceExecutions->update([
                "status" => "0",
                "fail_reason" => "Failed to fetch XML"
            ]);
            throw new Exception('Failed to fetch XML');
        }

        $xmlContent = $xmlResponse->body();
        $xml = simplexml_load_string($xmlContent, 'SimpleXMLElement', LIBXML_NOCDATA);
        if ($xml === false) {
            $epgServiceExecutions->update([
                "status" => "0",
                "fail_reason" => "Invalid XML"
            ]);
            throw new \Exception('Invalid XML');
        }

        $programmes = [];
        foreach ($xml->channel as $channel) {

            $channelId = (string) $channel['id'];
            $programmes = [];
            foreach ($xml->programme as $programme) {
                if ((string) $programme['channel'] === $channelId) {
                    $start = Carbon::createFromFormat(
                        'YmdHis O',
                        $programme['start'],
                        'UTC'
                    );

                    $end = Carbon::createFromFormat(
                        'YmdHis O',
                        $programme['stop'],
                        'UTC'
                    );

                    $formattedStartTime = $start->format('Y-m-d H:i:s');
                    $formattedEndTime   = $end->format('Y-m-d H:i:s');

                    $epgServiceExecutions->update([
                        'status' => '1',
                        "channel_id" => $channelId,
                        "epg_service_id" => $epgService->id,
                        "start_time" => $formattedStartTime,
                        "finish_time" => $formattedEndTime,
                    ]);

                    $epgPrograms = new EpgProgram();
                    $epgPrograms->channel_id = $channelId;
                    $epgPrograms->epg_service_id = $epgService->id;
                    $epgPrograms->epg_id = $epgServiceExecutions->id;
                    $epgPrograms->title = $programme->title;
                    $epgPrograms->description = $programme->desc;
                    $epgPrograms->start_date_time = $formattedStartTime;
                    $epgPrograms->end_date_time = $formattedEndTime;
                    $epgPrograms->category = (string) $programme['category'];
                    $epgPrograms->save();
                }
            }
        }
    }
}
