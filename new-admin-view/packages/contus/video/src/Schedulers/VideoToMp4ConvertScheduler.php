<?php

/**
 * LiveVideo Scheduler
 *
 * @name LiveVideoScheduler
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Schedulers;

use Contus\Base\Schedulers\Scheduler;
use Exception;
use Contus\Video\Repositories\FrontVideoRepository;
use Contus\Video\Repositories\AWSUploadRepository;
use Contus\Base\Repositories\UploadRepository;
use Contus\Video\Repositories\VideoCountriesRepository;
use Contus\Video\Repositories\VideoCastRepository;
use Contus\Video\Repositories\QuestionsRepository;
use Contus\Video\Repositories\CommentsRepository;
use Contus\Video\Models\Video;
class VideoToMp4ConvertScheduler extends Scheduler {
    /**
     * Class intializer
     *
     * @return void
     */
    protected $video = null;
    public function __construct() {
        parent::__construct ();
        $this->video = new Video();
    }
    /**
     * Scheduler frequency
     *
     * @param \Illuminate\Console\Scheduling\Event $event
     * @return void
     */
    public function frequency(\Illuminate\Console\Scheduling\Event $event) {
        $event->everyMinute();
    }
    /**
     * Scheduler call method
     * actual execution go's here
     *
     * @return \Closure
     */
    public function call() {
        return function () {
            $submittedJobs = Video::where('job_status', 'Convert to MP4')
            ->select('id', 'fine_uploader_name', 'fine_uploader_uuid')
            ->get();    
            foreach ($submittedJobs as $submittedJob) {
            $sourceFilePath =public_path().'/uploads/videos/files/'.$submittedJob['fine_uploader_uuid'].'/'.$submittedJob["fine_uploader_name"];
            $extensionArray = explode('.', $submittedJob["fine_uploader_name"]);
            $randFilename = $extensionArray[0].rand();
            $mp4FilePath =public_path().'/uploads/videos/files/'.$submittedJob['fine_uploader_uuid'].'/'.$randFilename.'.mp4';
            $rasio = exec("ffprobe -v error -select_streams v:0 -show_entries stream=height,width -of csv=s=x:p=0 ".$sourceFilePath);

            $height = explode('*',$rasio);
            if($height <= 240){
              $rasio = "426x240";
            }
            if(strtolower($extensionArray[1])=='mkv'){
               $ex= exec("ffmpeg -i " .$sourceFilePath. " -c copy ".$mp4FilePath);
            }else {
                // NOTE : Inorder to make the quanlity improvement we have chagned the command
                // $ex= exec("ffmpeg -i " .$sourceFilePath. " -acodec libvorbis -ac 2 -ab 96k -ar 44100 -b 345k -s ".$rasio." ".$mp4FilePath);
                $ex= exec("ffmpeg -i " .$sourceFilePath. " -c:v libx264 -crf 23 -profile:v high -ac 2 -ab 96k -ar 44100 -b 345k -s ".$rasio." ".$mp4FilePath);
            }
            $video = $this->video->findorfail($submittedJob['id']);
            $video->fine_uploader_name = $randFilename.'.mp4';
            $video->job_status = 'Video Uploaded';
            $video->transcode_status = 'Progressing';
            $video->save();
            unlink($sourceFilePath);
            }
        };
    }
}