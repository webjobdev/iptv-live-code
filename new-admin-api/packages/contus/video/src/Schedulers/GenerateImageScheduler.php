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
use App\Support\GenerateSpriteImage;
use Contus\Video\Models\VideoPreset;

class GenerateImageScheduler extends Scheduler {
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

            $presets        = VideoPreset::where('format','ts')->orderBy('is_active',1)->orderBy('id', 'desc')->first();

            $submittedJobs  = Video::where('sprite_image_status', 0)->first();

            if(!empty($submittedJobs) && !empty($presets)) {
                $submittedJobs->sprite_image_status = 1;
                $submittedJobs->save();
                try {
                    \Log::info(" IMAGE GENERATION ");
                    $class                  = new GenerateSpriteImage();
                    $videoInfo['width']     = 192;
                    $videoInfo['height']    = 108;
                    $videoInfo['video_id']  = $submittedJobs->id;
                    $videoInfo['prefix']    = $submittedJobs->aws_prefix;
                    $videoInfo['preset_id'] = $presets->aws_id;
                    $class->create_sprite($videoInfo);
                } catch (\Exception $exception) {
                    app('log')->error(' ###File : ' . $exception->getFile() . ' ##Line : ' . $exception->getLine() . ' #Error : ' . $exception->getMessage());
                }
            }
        };
    }
}