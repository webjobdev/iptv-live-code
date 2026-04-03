<?php
/**
 * Observer to handle video model actions
 *
 * @name VideoObserver
 * @vendor Contus
 * @package Base
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2020 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */

namespace Contus\Video\Observer;

use App\Jobs\SendNotifications;
use Contus\Base\Handlers\GridHandler;
use Contus\Video\Models\Video;

class VideoObserver
{
    use GridHandler;
    /**
     * Class intializer
     *
     * @return void
     */
    public function __construct()
    {
    }
    /**
     * Method to handle when video data is created.
     *
     * @param int $videoId
     * @return void
     */
    public function created(Video $video)
    {
        if ($video->id != '') {
            $videoDetails['id'] = $video->id;
            $endpoint = 'add?_id=' . $video->id;
            $this->callElasticsearchService('POST', $endpoint, $videoDetails);
        }
    }
    /**
     * Method to handle when video data is updated.
     *
     * @param int $videoId
     * @return void
     */
    public function updated(Video $video)
    {
        if ($video->job_status == 'Complete' && $video->is_active == 1) {
            $videoDetails['job_status'] = $video->job_status;
            $videoDetails['is_active'] = $video->is_active;
            $endpoint = 'add?_id=' . $video->id;
            $this->callElasticsearchService('PUT', $endpoint, $videoDetails);
            if ($video->is_notify && !$video->is_notified) {
                $video->is_notified = 1;
                $video->save();
                $this->dispatchNewVideoNotification($video);
            }
        }
    }
    /**
     * Method to publish the message to payload
     *
     * @param object $videoInstance
     * @retutn void
     */
    public function dispatchNewVideoNotification($videoInstance)
    {
        try {
            //Dispatch payload to queue to notify customers
            $payload = [];
            $payload['videoID'] = $videoInstance->id;
            $payload['notifyOn'] = 'new_video';
            SendNotifications::dispatch($payload);
        } catch (\Exception $exception) {
            app('log')->error(' ###File : ' . $exception->getFile() . ' ##Line : ' . $exception->getLine() . ' #Error : ' . $exception->getMessage());
        }
    }
}
