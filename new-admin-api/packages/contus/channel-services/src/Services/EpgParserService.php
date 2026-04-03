<?php

namespace Contus\ChannelServices\Services;

use Contus\Channel\Model\Channel;
use Contus\ChannelServices\Model\EpgProgram;
use Contus\ChannelServices\Model\EpgService;
use Contus\ChannelServices\Model\EpgServiceExecution;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class EpgParserService
{
    /**
     * Parse EPG XML from the source URL.
     *
     * @param EpgService $epgService
     * @param string $executedBy
     * @return bool
     */
    public function parse($epgService, $executedBy = 'System')
    {
        $epgService = ($epgService instanceof EpgService) ? $epgService : EpgService::find($epgService);

        if (!$epgService) {
            throw new Exception('EPG Service not found');
        }

        $epgServiceExecutions = new EpgServiceExecution();
        $epgServiceExecutions->epg_service_id = $epgService->id;
        $epgServiceExecutions->status = "0";
        $epgServiceExecutions->executed_by = $executedBy;
        $epgServiceExecutions->save();

        $formattedStartTime = null;
        $formattedEndTime = null;

        try {

            $sourceUrl = trim($epgService->source_url);

            /* -------------------------------------------------
               1. Decide Source Type (XML or M3U)
            --------------------------------------------------*/
            if (str_ends_with(strtolower($sourceUrl), '.xml')) {

                // ✅ DIRECT XML
                $xmlUrl = $sourceUrl;

            } else {

                // ✅ M3U FILE
                $response = Http::withOptions([
                    'timeout' => 120,
                    'connect_timeout' => 10,
                ])->get($sourceUrl);

                if ($response->failed()) {
                    throw new Exception('Unable to fetch M3U file');
                }

                $m3uContent = $response->body();
                $lines = preg_split("/\r\n|\n|\r/", $m3uContent);

                $xmlUrls = array_values(array_filter($lines, function ($line) {
                    $line = trim($line);
                    return !empty($line)
                        && !str_starts_with($line, '#')
                        && str_ends_with(strtolower($line), '.xml');
                }));

                if (empty($xmlUrls)) {
                    throw new Exception('No XML URL found in M3U');
                }

                $xmlUrl = trim($xmlUrls[0]);
            }

            /* -------------------------------------------------
               2. Fetch XML
            --------------------------------------------------*/
            $xmlResponse = Http::withOptions([
                'timeout' => 120,
                'connect_timeout' => 10,
            ])->withHeaders([
                        'Accept' => 'application/xml',
                    ])->get($xmlUrl);

            if ($xmlResponse->failed()) {
                throw new Exception('Failed to fetch XML');
            }

            $xmlContent = $xmlResponse->body();

            // Fix invalid ampersands
            $xmlContent = preg_replace('/&(?!#?[a-z0-9]+;)/i', '&amp;', $xmlContent);

            $xml = simplexml_load_string($xmlContent, 'SimpleXMLElement', LIBXML_NOCDATA);
            if ($xml === false) {
                throw new Exception('Invalid XML format');
            }

            /* -------------------------------------------------
               3. Parse & Store Programmes
            --------------------------------------------------*/
            $completedCount = 0;

            foreach ($xml->programme as $programme) {

                $channelId = (string) $programme['channel'];

                $start = Carbon::createFromFormat('YmdHis O', (string) $programme['start'], 'UTC');
                $end = Carbon::createFromFormat('YmdHis O', (string) $programme['stop'], 'UTC');

                $formattedStartTime = $start->format('Y-m-d H:i:s');
                $formattedEndTime = $end->format('Y-m-d H:i:s');

                $exists = EpgProgram::where('channel_id', $channelId)
    ->where('epg_service_id', $epgService->id)
    ->where('start_date_time', $formattedStartTime)
    ->where('end_date_time', $formattedEndTime)
    ->exists();
    
if (!$exists) {

    EpgProgram::create([
        'channel_id'        => $channelId,
        'epg_service_id'    => $epgService->id,
        'epg_id'            => $epgServiceExecutions->id,
        'title'             => (string) $programme->title,
        'description'       => (string) $programme->desc,
        'start_date_time'   => $formattedStartTime,
        'end_date_time'     => $formattedEndTime,
        'category'          => (string) $programme->category,
        'image'             => isset($programme->icon['src'])
                                ? (string) $programme->icon['src']
                                : null,
    ]);

                $completedCount++;
            }

            /* -------------------------------------------------
               4. Execution Status
            --------------------------------------------------*/
            $epgServiceExecutions->update([
                'status' => '1',
                'completed_programmes' => $completedCount,
                'start_time' => $formattedStartTime,
                'finish_time' => $formattedEndTime,
            ]);

            return true;

        } catch (\Exception $e) {

            Log::error("EPG Error: " . $e->getMessage());

            $epgServiceExecutions->update([
                'status' => '0',
                'fail_reason' => substr($e->getMessage(), 0, 250),
                'start_time' => $formattedStartTime,
                'finish_time' => $formattedEndTime,
            ]);

            return false;
        }
    }

    protected function calculateNextRun($scheduleBase)
    {
        switch ($scheduleBase) {
            case 'hourly':
                return Carbon::now()->addHour();
            case 'daily_midnight':
                return Carbon::now()->addDay()->startOfDay();
            case 'weekly_sunday':
                return Carbon::now()->next(Carbon::SUNDAY)->setHour(2)->startOfHour();
            case 'monthly_1st':
                return Carbon::now()->addMonth()->startOfMonth()->setHour(3)->startOfHour();
            default:
                return null;
        }
    }
}



// if (!$epgService->is_active) {
//     return false;
// }

// $startTime = Carbon::now();
// $execution = EpgServiceExecution::create([
//     'epg_service_id' => $epgService->id,
//     'status' => 'Running',
//     'start_time' => $startTime,
//     'executed_by' => $executedBy,
// ]);

// $debugInfo = "";

// try {
//     $response = Http::withOptions([
//         'verify' => false,
//         'timeout' => 60,
//     ])->get($epgService->source_url);

//     if (!$response->successful()) {
//         throw new \Exception("Download Failed. Status: " . $response->status());
//     }

//     $xmlContent = trim($response->body());

//     // Decompress if needed
//     if (str_ends_with(strtolower($epgService->source_url), '.gz') || bin2hex(substr($xmlContent, 0, 2)) === '1f8b') {
//         $xmlContent = gzdecode($xmlContent);
//     }

//     $xml = simplexml_load_string($xmlContent);
//     if ($xml === false) {
//         throw new \Exception("XML Parse Failed.");
//     }

//     $completedCount = 0;
//     $shiftPostfix = trim($epgService->shift_postfix ?? '');

//     // Use XPath to find all programmes regardless of structure
//     $programmes = $xml->xpath('//programme');
//     $totalInXml = count($programmes);

//     $debugInfo .= "XML Progs: {$totalInXml}. Postfix: '{$shiftPostfix}'. ";
//     $matchSample = [];

//     foreach ($programmes as $index => $prog) {
//         $epgIdInXml = (string) $prog['channel'];
//         $epgIdToMatch = trim($epgIdInXml . $shiftPostfix);

//         // Find ALL channels that match this EPG ID
//         $channels = Channel::where('epg_id', $epgIdToMatch)->get();

//         // Log first 3 matches/misses for debugging
//         if ($index < 3) {
//             $matchSample[] = "[{$epgIdInXml}]->[{$epgIdToMatch}]" . ($channels->count() > 0 ? "OK(" . $channels->count() . ")" : "MISS");
//         }

//         foreach ($channels as $channel) {
//             $rawStart = (string) $prog['start'];
//             $rawStop = (string) $prog['stop'];

//             try {
//                 $start = Carbon::createFromFormat('YmdHis O', $rawStart);
//                 $stop = Carbon::createFromFormat('YmdHis O', $rawStop);
//             } catch (\Exception $e) {
//                 $start = Carbon::parse($rawStart, $epgService->time_zone);
//                 $stop = Carbon::parse($rawStop, $epgService->time_zone);
//             }

//             EpgProgram::updateOrCreate(
//                 [
//                     'channel_id' => $channel->id,
//                     'start_date_time' => $start->timezone('UTC')->toDateTimeString(),
//                 ],
//                 [
//                     'epg_service_id' => $epgService->id,
//                     'epg_id' => $epgIdInXml,
//                     'title' => (string) $prog->title,
//                     'description' => (string) $prog->desc,
//                     'end_date_time' => $stop->timezone('UTC')->toDateTimeString(),
//                     'category' => (string) $prog->category,
//                 ]
//             );
//             $completedCount++;
//         }
//     }

//     $debugInfo .= "Sample: " . implode(", ", $matchSample);

//     $execution->update([
//         'status' => 'OK',
//         'completed_programmes' => $completedCount,
//         'finish_time' => Carbon::now(),
//     ]);

//     $epgService->update([
//         'last_run' => Carbon::now(),
//         'next_run' => $this->calculateNextRun($epgService->schedule_base),
//     ]);

//     return true;

// } catch (\Exception $e) {
//     Log::error("EPG Error: " . $e->getMessage());
//     $execution->update([
//         'status' => 'Failed',
//         'fail_reason' => substr($e->getMessage(), 0, 250),
//         'finish_time' => Carbon::now(),
//     ]);
//     return false;
// }