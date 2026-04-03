<?php

/**
 * Upload To S3 Scheduler
 *
 * @name AudioUploadToS3Scheduler
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2018 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Audio\Schedulers;

use Contus\Base\Schedulers\Scheduler;
use Aws\S3\S3Client;
use Aws\ElasticTranscoder\ElasticTranscoderClient;
use Contus\Audio\Models\Audios;
use Contus\Audio\Models\TranscoderTracking;
use Contus\Audio\Repositories\AWSUploadRepository;
use Exception;
use Contus\Audio\Helpers\DeletedAudioException;

class AudioUploadToS3Scheduler extends Scheduler{
    /**
     * Class property to hold audio instance
     *
     * @var \Contus\audio\Models\Audio
     */
    protected $audio = null;

    /**
     * Class property to hold AWSUploadRepository instance
     *
     * @var \Contus\Audio\Repositories\AWSUploadRepository
     */
    protected $awsRepository = null;

    /**
     * Class intializer
     *
     * @return void
     */
    public function __construct(){
        parent::__construct();
        
        $this->audio = new Audios();
        $this->transcoderTracking = new TranscoderTracking();
        $this->awsRepository = new AWSUploadRepository();
        $this->transcodeType = env('AUDIO_TRANSCODE_TYPE');
        $this->audiofileBasePath = 'public' . DIRECTORY_SEPARATOR . config('contus.audio.audiomedia.audio.temporary_storage_path');
        $this->audioS3SourceFolderName = config("contus.audio.audiomedia.audio.s3_location_audio_source");
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
            $submittedJobs = $this->audio->where('job_status', 'Audio Uploaded')->where('fine_uploader_name', '!=', '')->where('is_archived', '!=', 1)->select('id', 'fine_uploader_name', 'fine_uploader_uuid', 'creator_id')->get();
            foreach ($submittedJobs as $submittedJob) {
                try {
                $extensionArray = explode('.', $submittedJob["fine_uploader_name"]);
                $extension = end($extensionArray);
                $fileSlug = $submittedJob["id"] . '-audio-' . rand(100000, 999999);
                $filename = $fileSlug . '.' . $extension;
                $file = base_path($this->audiofileBasePath . DIRECTORY_SEPARATOR . $submittedJob["fine_uploader_uuid"] . DIRECTORY_SEPARATOR . $submittedJob["fine_uploader_name"]);
                    // Validate the file and delete it if it is not valid.
                    if (! $this->isValidFile($file)) {
                        app('log')->error('Invalid file type');
                        $this->setErrorStatus($submittedJob);
                        continue;
                    }
                    // Change status of the audio to Uploading before uploading.
                    $getID3 = new \getID3();
                    $fileGetProperties = $getID3->analyze($file);
                    $uploadingAudio = $this->audio->findorfail($submittedJob["id"]);
                    if ($uploadingAudio->is_archived === 1) {
                        throw new DeletedAudioException();
                    }
                    $audio_duration	 = formatTime($fileGetProperties['playtime_string']);
                    $uploadingAudio->audio_duration	 = $audio_duration;
                    $uploadingAudio->job_status = 'Uploading';
                    if (config()->get('settings.aws-settings.aws-general.aws_payer_hls') == 'Yes') {
                        $uploadingAudio->is_hls = 1;
                    }
                    $uploadingAudio->save();
                    $filename =$this->audioS3SourceFolderName.$filename;
                    $audioUrl = $this->awsRepository->uploadFileToS3($file, $filename);
                    if ($audioUrl) {
                        $audioData = array(
                            'id' => $submittedJob["id"],
                            'title' => $submittedJob["fine_uploader_name"],
                            'audio_url' => $audioUrl,
                            'audio_duration' => $audio_duration,
                            'file_slug' => $fileSlug,
                            'extension' => $extension,
                            'fine_uploader_uuid' => $submittedJob["fine_uploader_uuid"],
                            'creator_id' => $submittedJob['creator_id']
                        );
                        $uploadingAudio->audio_url = $audioUrl;
                        $uploadingAudio->job_status = 'Uploaded';
                        $uploadingAudio->save();                        
                        if ($this->transcodeType != 'FFMPEG') {
                                $this->saveNewAudioDetails($audioData, []);
                            /** 
                            *   @todo
                            *   $transInfo = $this->getTranscodedHours();
                            *   $limtTime = (int) env('TRANSCODE_LIMIT', 0);
                            *   $trackTime = totalTrackTime($audioData['audio_duration']);
                            *    if($limtTime  == 0) {
                            *        $this->saveNewAudioDetails($audioData, []);
                            *    } else {
                            *        if(($trackTime['minute'] < $transInfo['remMin']) || (($trackTime['second'] <= $transInfo['remSec']) && ($trackTime['minute'] <= $transInfo['remMin']))) {
                            *            $this->saveNewAudioDetails($audioData, $trackTime);
                            *        }
                            *        else {
                            *            $uploadingAudio->job_status = 'Error';
                            *            $uploadingAudio->save(); 
                            *            \Log::info("LIMIT EXCEEDED FOR : ". $uploadingAudio->id);
                            *        }
                            *    }
                            */
                        }
                    }
                } catch (Exception $exception) {
                    app('log')->error(' ###File : ' . $exception->getFile() . ' ##Line : ' . $exception->getLine() . ' #Error : ' . $exception->getMessage());
                    $this->setErrorStatus($submittedJob);
                }
            }
        };
    }

    /**
     * Function to save details of the uploaded audio in the database and trigger transcoding for that audio.
     *
     * @param array $audioData The details of the new audio.
     * @return boolean true if audio is saved successfully and False if not.
     */
    function saveNewAudioDetails($audioData, $trackTime = [])
    {
        /**
         * Get pipeline id of Elastic Transcoder from settings cache file.
         */
        $pipelineId = env('AWS_PIPELINE_ID');
        /**
         * Trigger transcoding process of the audio uploaded.
         */
        $inputFile = $this->audioS3SourceFolderName.$audioData['file_slug'] . '.' . $audioData['extension'];
        $jobId = $this->awsRepository->transcodeFile($pipelineId, $inputFile, $audioData['file_slug'], $audioData['id'], $audioData['creator_id']);
        $isJobId = false;
        if ($jobId) {
            /**
             * Update job id in the audios table.
             */
            $audioModel = $this->audio->findorfail($audioData['id']);
            $audioModel->pipeline_id = $pipelineId;
            $audioModel->audio_url = $audioData['audio_url'];
            $audioModel->job_id = $jobId;
            $audioModel->job_status = 'Progressing';
            $audioModel->save();
            $isJobId = true;

            $trackTime = totalTrackTime($audioData['audio_duration']);
            $transTrack = $this->transcoderTracking;
            $transTrack->audio_id = (int) $audioData['id'];
            $transTrack->audio_duration = $audioData['audio_duration'];
            $transTrack->minutes = $trackTime['minute'];
            $transTrack->seconds = $trackTime['second'];
            $transTrack->is_active = 1;
            $transTrack->save();
        }
        return $isJobId;
    }

    /**
     * Function to check if a file is valid audio file or not.
     *
     * @param string $file The path of the file which is being validated.
     * @return boolean True if the file is valid and False if not.
     */
    function isValidFile($file){
        $validFileTypes = [
            'audio/mpeg',
            'audio/wav',
            'audio/x-wav',
            'audio/x-pn-wav',
            'audio/wave',
        ];
        return (in_array(mime_content_type($file), $validFileTypes)) ? 1 : 0;
    }
    /**
     * FUnction to delete invalid file and set the status of the audio to error.
     *
     * @param array $submittedJob
     *            The details of the file.
     */
    function setErrorStatus($submittedJob){
        $audio = $this->audio->findorfail($submittedJob["id"]);
        $audio->job_status = 'Error';
        $audio->save();
        
        $filePath = base_path($this->audiofileBasePath . DIRECTORY_SEPARATOR . $submittedJob["fine_uploader_name"]);
        $folderPath = base_path($this->audiofileBasePath . DIRECTORY_SEPARATOR . $submittedJob["fine_uploader_uuid"]);
        if (file_exists($filePath)) {
            unlink($filePath);
        }
        if (file_exists($folderPath)) {
            rmdir($folderPath);
        }
    }
}