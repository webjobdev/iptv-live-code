<?php

/**
 * Transcoder Scheduler
 *
 * @name TranscoderScheduler
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2020 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Schedulers;

use Contus\Base\Schedulers\Scheduler;
use Contus\Video\Models\TranscodedVideo;
use Contus\Video\Models\Video;
use Contus\Video\Models\VideoPreset;
use Contus\Video\Repositories\AWSUploadRepository;
use Exception;
use Storage;

class AWSTranscoderScheduler extends Scheduler
{

    /**
     * Class property to hold Video instance
     *
     * @var \Contus\Video\Models\Video
     */
    protected $video = null;

    /**
     * Class property to hold AWSUploadRepository instance
     *
     * @var \Contus\Video\Repositories\AWSUploadRepository
     */
    protected $awsRepository = null;

    /**
     * Class intializer
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->video = new Video();
        $this->awsRepository = new AWSUploadRepository(new TranscodedVideo(), new VideoPreset());
    }

    /**
     * Scheduler frequency
     *
     * @param \Illuminate\Console\Scheduling\Event $event
     * @return void
     */
    public function frequency(\Illuminate\Console\Scheduling\Event $event)
    {
        $event->everyMinute();
    }

    /**
     * Scheduler call method
     * actual execution go's here
     *
     * @return \Closure
     */
    public function call()
    {
        //\Log::info('test');
        return function () {
            $transcodeType = env('VIDEO_TRANSCODE_TYPE');
            //\Log::info($transcodeType);

            $submittedJobs = Video::select('id', 'title', 'video_url', 'fine_uploader_name', 'fine_uploader_uuid', 'creator_id')
                ->where('video_url', '!=', '')
                ->where('job_status', 'Video Uploaded')
                ->where('is_archived', 0)
                ->get();
            foreach ($submittedJobs as $submittedJob) {
                try {
                    $splitUrl = explode('/', $submittedJob['video_url']);
                    $fileNameWthExt = end($splitUrl);
                    $splitFilename = explode('.', $fileNameWthExt);
                    $extension = end($splitFilename);

                    $videoData = array(
                        'id' => $submittedJob['id'],
                        'title' => $submittedJob['title'],
                        'video_url' => $submittedJob['video_url'],
                        'file_slug' => $submittedJob["id"] . '-video-' . rand(100000, 999999),
                        'extension' => $extension,
                        'creator_id' => $submittedJob['creator_id'],
                        'video_height' => '2160',
                    );

                    if ($transcodeType != 'FFMPEG') {
                        $this->saveNewVideoDetails($videoData);
                    }
                } catch (Exception $exception) {
                    app('log')->error('AWSTranscoderScheduler: ###File : ' . $exception->getFile() . ' ##Line : ' . $exception->getLine() . ' #Error : ' . $exception->getMessage());
                }
            }
        };
    }

    /**
     * Function to save details of the uploaded video in the database and trigger transcoding for that video.
     *
     * @param array $videoData
     *            The details of the new video.
     * @return boolean True if video is saved successfully and False if not.
     */
    public function saveNewVideoDetails($videoData)
    {
        /**
         * Get pipeline id of Elastic Transcoder from settings cache file.
         */
        $pipelineId = env('AWS_PIPELINE_ID');
        app('log')->info($pipelineId . ' 1 initiated');
        /**
         * Trigger transcoding process of the video uploaded.
         */
        $splitSrcPath = parse_url($videoData['video_url']);
        $videoSrcPath = substr_replace($splitSrcPath['path'], "", 0, 1);
        $inputFile = $videoSrcPath;
        $jobId = $this->awsRepository->transcodeFile($pipelineId, $inputFile, $videoData);
        $isJobId = false;
        if ($jobId) {
            /**
             * Update job id in the videos table.
             */

            $videoSize = $this->getFileSize($videoData['video_url']);

            $video = $this->video->findorfail($videoData['id']);
            $video->pipeline_id = $pipelineId;
            $video->video_url = $videoData['video_url'];
            $video->job_id = $jobId;
            $video->video_size = $videoSize;
            $video->job_status = 'Uploaded';
            $video->save();
            $isJobId = true;
        }
        return $isJobId;
    }

    public function getFileSize($s3Url)
    {
        $fileSizeInMegabytes = null;

        if (empty($s3Url)) {
            return $fileSizeInMegabytes;
        }

        try {
            $filePath = parse_url($s3Url, PHP_URL_PATH);
            $filePath = explode("/", $filePath);
            if(isset($filePath[1])) {
                unset($filePath[1]);
            }
            $filePath = implode('/',$filePath);
            $fileSizeInBytes = Storage::disk('s3')->size($filePath);
            if ($fileSizeInBytes < 1000000000) {
                $fileSizeInMegabytes = $fileSizeInBytes / 1000000; // bytes to mega bytes
                return number_format($fileSizeInMegabytes, 2) . "MB";
            }

            $fileSizeInGigaByte = $fileSizeInBytes / 1000000000; // bytes to giga bytes
            return number_format($fileSizeInGigaByte, 2) . "GB";

        } catch (Exception $exception) {
            app('log')->error('AWSTranscoderScheduler->getFileSize: ###File : ' . $exception->getFile() . ' ##Line : ' . $exception->getLine() . ' #Error : ' . $exception->getMessage());

            return $fileSizeInMegabytes;
        }
    }
}
