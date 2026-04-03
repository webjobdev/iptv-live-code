<?php

/**
 * Audio Transcoder Job Status Scheduler
 *
 * @name AudioTranscoderJobStatusScheduler
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2018 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */

namespace Contus\Audio\Schedulers;

use Contus\Base\Schedulers\Scheduler;
use Aws\ElasticTranscoder\ElasticTranscoderClient;
use Contus\Audio\Models\Audios;
use Aws\S3\S3Client;
use Illuminate\Support\Facades\Storage;
use Contus\Audio\Repositories\AWSUploadRepository;
use Carbon\Carbon;
use Psy\Exception\FatalErrorException;
use Contus\Notification\Repositories\NotificationRepository;

class AudioTranscoderJobStatusScheduler extends Scheduler
{
    /**
     * Class property to hold Video instance
     *
     * @var \Contus\Video\Models\Video
     */
    protected $audio = null;
    protected $awsRepository = null;

    /**
     * Class intializer
     *
     * @return void
     */
    public function __construct(){
        parent::__construct();
        $this->audio = new Audios();
        $this->awsRepository = new AWSUploadRepository();
        $this->audiofileBasePath = 'public' . DIRECTORY_SEPARATOR . config('contus.audio.audiomedia.audio.temporary_storage_path');
        $this->awsS3Bucket = env('AWS_BUCKET');
        $this->awsConfigurations = array(
                'region' => env('AWS_REGION'),
                'version' => env('AWS_VERSION'),
                'credentials' => [
                    'key' => env('AWS_KEY'),
                    'secret' => env('AWS_SECRET')
                ]
            );
    }
    /**
     * Scheduler frequency
     *
     * @param \Illuminate\Console\Scheduling\Event $event
     * @return void
     */
    public function frequency(\Illuminate\Console\Scheduling\Event $event){
        $event->everyMinute();
    }

    /**
     * Scheduler call method
     * actual execution go's here
     *
     * @return \Closure
     */
    public function call(){
        return function () {
            /**
             * In this function, all the unfinished jobs are retrieved from the database.
             * Their status are checked with AWS and updated in the database.
             */
            $unfinishedJobs = $this->audio->where('job_status', '!=', 'Complete')->where('job_status', '!=', 'Uploading')->where('job_id', '!=', '')->get();
            foreach ($unfinishedJobs as $unfinishedJob) {
                    try {
                        $client = ElasticTranscoderClient::factory($this->awsConfigurations);
                        $result = $client->readJob(array('Id' => $unfinishedJob ['job_id']));

                        if ($result ['Job']) {
                            $jobStatus = $result ['Job'] ['Status'];
                            $percent = $this->awsRepository->getAWSProgressPercent($result['Job']);
                            /**  Update job status in the database. */
                            $audioInstance = $this->audio->findorfail($unfinishedJob ['id']);
                            $audioInstance->job_status = $jobStatus;
                            /** 
                             * @todo
                             * $audioInstance->slug = $this->audio->generateDynamicSlug($audioInstance);
                             * $audioInstance->upload_percentage = $percent;
                             */
                            $audioInstance->is_active = 1;
                            $audioInstance->save();
                            /** Delete the fine uploader file in the server if the job status is Complete. */
                            if ($jobStatus == "Complete") {
                                /** 
                                 * @todo
                                 * $notifyObj = new NotificationRepository();
                                 * $notifyObj->notify('video', $audioInstance->id);
                                 */
                                $filePath = base_path($this->audiofileBasePath . DIRECTORY_SEPARATOR . $audioInstance->fine_uploader_name);
                                $folderPath = base_path($this->audiofileBasePath . DIRECTORY_SEPARATOR . $audioInstance->fine_uploader_uuid);
                                if (file_exists($filePath)) {
                                    unlink($filePath);
                                }
                                if (file_exists($folderPath)) {
                                    rmdir($folderPath);
                                }
                            }
                        }
                    } catch (\Exception $exception) {
                        app('log')->error(' ###File : ' . $exception->getFile() . ' ##Line : ' . $exception->getLine() . ' #Error : ' . $exception->getMessage());
                    }
                }
        };
    }
}
