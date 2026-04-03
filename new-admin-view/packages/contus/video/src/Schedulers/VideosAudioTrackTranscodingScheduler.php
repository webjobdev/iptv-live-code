<?php
/**
 * Transcode video lingual audio tracks
 *
 * @name VideosAudioTrackTranscodingScheduler
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2019 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Schedulers;

use Contus\Base\Schedulers\Scheduler;
use Contus\Video\Models\VideoAudioUploads;
use Contus\Video\Models\Video;
use Contus\Video\Repositories\AWSUploadRepository;
use Contus\Video\Models\TranscodedVideo;
use Contus\Video\Models\VideoPreset;
use Aws\ElasticTranscoder\ElasticTranscoderClient;
use Contus\Video\Schedulers\TranscoderJobStatusScheduler;
use Exception;

class VideosAudioTrackTranscodingScheduler extends Scheduler{
    /**
     * Class intializer
     *
     * @return void
     */
    public function __construct(){
        parent::__construct();
        $this->video = new Video();
        $this->audioTracksModel = new VideoAudioUploads();
        $this->awsRepository = new AWSUploadRepository(new TranscodedVideo(), new VideoPreset());
        $this->audioTrackS3SourcePath = config("contus.base.media.video_lingual_audio_tracks.s3_location_audio_source");
        $this->AWSPipelineId = env('AWS_PIPELINE_ID');
        $this->audioAWSPresetID = env('VIDEO_LINGUAL_AUDIO_PRESET_ID');
        $this->awsBucketURL = env('AWS_BUCKET_URL');
        $this->AWSConfigurations = array(
            'region' => env('AWS_REGION'),
            'version' => env('AWS_VERSION'),
            'credentials' => [
                'key' => env('AWS_KEY'),
                'secret' => env('AWS_SECRET')
            ]
        );
        $this->AWSBucket = env('AWS_BUCKET');
        $this->transcoderScheduler = new TranscoderJobStatusScheduler();
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
            $this->performTranscoding();
            $this->trackTranscoding();
            $this->addAudioTrackIntoVideosHLSFile();
        };
    }
    /**
     * Method to perform transcoding of audio tracks
     * 
     * @return void
     */
    public function performTranscoding(){
        $jobLists = $this->audioTracksModel
                            ->select('id', 'video_id', 'audio_title', 'audio_src_url')
                            ->where('job_status' ,'Audio Uploaded')
                            ->where('audio_src_url','!=','')->get();
        foreach($jobLists as $job){
            try {
                $this->transcodeAudioFiles($job['audio_src_url'], $job['video_id'], $job['audio_title'], $job['id']);
            } catch (Exception $exception){
                app('log')->error(' ###File : ' . $exception->getFile() . ' ##Line : ' . $exception->getLine() . ' #Error : ' . $exception->getMessage());
            }
        }     
    }
    /**
     * Method to transcode the audio tracks
     * 
     * @param string $sourceFile
     * @param int $videoID
     * @param string $audioTitle
     * @param int $audioTrackID
     * 
     * @return void
     */
    public function transcodeAudioFiles($sourceFilePath, $videoID, $audioTitle, $audioTrackID){
        $destinationFilePath = $videoAWSPrefix = $jobID = $hlsPlaylistURL = $videoJobStatus = $videoAWSPrefix = '';
        $outputKey = $outputs = $playlistConfig = $presets = $columns = array();
        $columns = ['aws_prefix','job_status'];
        $videoData = $this->obtainVideoData($videoID, $columns);
        $videoJobStatus = $videoData->job_status;
        $videoAWSPrefix = $videoData->aws_prefix;
        if($videoJobStatus == 'Complete' && $videoAWSPrefix != ""){
            $client = $this->awsRepository->awsETClient;
            $sourceFileName = $this->getFilename($sourceFilePath);
            $sourceFilenameWithEXT = $this->getFilename($sourceFilePath, true);
            $audioSourceFolder = $this->audioTrackS3SourcePath.$sourceFilenameWithEXT;
            $destinationFilePath = $videoAWSPrefix. '/'. 'audios'. '/'. $audioTitle . '/'. $sourceFileName;
            $audioOutputKey = 'audio-' . $this->audioAWSPresetID;
            $presets = explode(',', $this->audioAWSPresetID);
            foreach ( $presets as $presetID ) {
                $audioKey = 'audio-' . $presetID;
                $output = array ('Key' => $audioKey,'Rotate' => 'auto','PresetId' => $presetID);
                $output ['SegmentDuration'] = '5';
                $outputKey [] = $audioKey;
                $outputs [] = $output;
            }
            $playlistConfig  = [ [ 
                'Name' => 'playlist', 
                'Format' => 'HLSv3', 
                'OutputKeys' => $outputKey, 
                'HlsContentProtection' => [ 'Method' => 'aes-128','KeyStoragePolicy' => 'WithVariantPlaylists' ] 
                ] ];
            $createJob =  $client->createJob ( 
                array (
                    'PipelineId' =>  $this->AWSPipelineId,
                    'Input' => array ('Key' => $audioSourceFolder ),
                    'Outputs' => $outputs,
                    'OutputKeyPrefix' => $destinationFilePath . '/',
                    'Playlists' => $playlistConfig 
                    ) 
                );
            if($createJob['Job']){
                $jobID = $createJob['Job']['Id'];
                $hlsPlaylistURL = $this->awsBucketURL. $destinationFilePath. '/'. 'playlist'. '.m3u8';
                $audioTracksModel = $this->audioTracksModel->findorfail($audioTrackID);
                $audioTracksModel->audio_hls_url = $hlsPlaylistURL;
                $audioTracksModel->pipeline_id = $this->AWSPipelineId;
                $audioTracksModel->audio_hls_prefix = 'audios'. '/'. $audioTitle . '/'. $sourceFileName;
                $audioTracksModel->job_id = $jobID;
                $audioTracksModel->job_status = 'Progressing';
                $audioTracksModel->save();
            }
        }
    }
    /**
     * Method to get the file name form the audio source
     * 
     * @param string $sourceFile
     * @return string
     */
    public function getFilename($sourceFile, $withEXT = null){
        $splitURL = explode('/',$sourceFile);
        $filenameArr = ($withEXT) ? end($splitURL) : (explode('.',end($splitURL)));
        return ($withEXT) ? $filenameArr : $filenameArr[0];
    }
    /**
     * Method to obtain videos data for a column
     * 
     * @param int $videoID
     * @param array $columns
     * @return string
     */
    public function obtainVideoData($videoID, $columns){
        return $this->video->select($columns)->where('id',$videoID)->first();
    }
    /**
     * Method to track the audio transcoding status
     * 
     * @return void
     */
    public function trackTranscoding(){
        $jobStatus = '';
        $jobLists = $this->audioTracksModel->select('id', 'job_id')->where('job_status', '!=', 'Complete')->where('job_id', '!=', '')->get();
        foreach ($jobLists as $job) {
            try{
                $AWSTranscoderClient = ElasticTranscoderClient::factory($this->AWSConfigurations);
                $jobResult = $AWSTranscoderClient->readJob(array('Id' => $job ['job_id']));
                if ($jobResult ['Job']) {
                    $jobStatus = $jobResult ['Job'] ['Status'];
                    $audioTracksModel = $this->audioTracksModel->findorfail($job ['id']);
                    $audioTracksModel->job_status = $jobStatus;
                    $audioTracksModel->save();
                }
                if($jobStatus == 'Complete'){
                    $this->transcoderScheduler->applyEncryptionTechnique($jobResult);
                }
            } catch(\Exception $exception){
                app('log')->error(' ###File : ' . $exception->getFile() . ' ##Line : ' . $exception->getLine() . ' #Error : ' . $exception->getMessage());
            }
        }
    }
    /**
     * Method to include the lingual track files into the video's main hls file
     * 
     * @return void
     */
    public function addAudioTrackIntoVideosHLSFile(){
        $unfinishedAudioJobs = $this->audioTracksModel
        ->where('job_status', '=', 'Complete')
        ->where('audio_hls_prefix', '!=', '')
        ->where('video_hls_update_status', '=', 0)->get();
        if($unfinishedAudioJobs){
            foreach($unfinishedAudioJobs as $unfinishedAudioJob){
                    $videoDetails= $this->video->where('id','=',$unfinishedAudioJob->video_id)->first();
                    $this->applyAudioDetails($unfinishedAudioJob,$videoDetails->aws_prefix, $videoDetails->hls_playlist_url, $unfinishedAudioJob->video_id);
            }
        }    
    }
    /**
     * Method to update audio data into the video's playlist file
     * 
     * @param array $transcodeResult
     * @param string $key
     * @param string $videoHLSURL
     * @param int $videoIDs
     * 
     * @return void
     */
    public function applyAudioDetails($transcodeResult, $key, $videoHLSURL, $videoID){
        $isFileMoved = false;
        $audioPresetFiles = array();
        $videoCurrentFilename = $this->getFilename($videoHLSURL, true);
        $videoHLSFile = $key . '/' . $videoCurrentFilename;
        $result = $this->awsRepository->fetchFileFromS3Bucket($videoHLSFile);
        $playlist = (string) $result['Body'];
        $search= '#EXTM3U';
        $audioTrackMainURI = $key. '/'. $transcodeResult->audio_hls_prefix. '/'. 'playlist.m3u8';
        $audioMainHLSContent = $this->awsRepository->fetchFileFromS3Bucket($audioTrackMainURI);
        $audioMainHLSContentString = (string) $audioMainHLSContent['Body'];
        $splitAudioMainHLSContentString = explode("\n", $audioMainHLSContentString);
        // Set Default audio track
        if(strpos($playlist, 'DEFAULT=YES') === false){
            $defaultAudioTrackStr = $search. "\n". '#EXT-X-MEDIA:TYPE=AUDIO,GROUP-ID="stereo",LANGUAGE="ta",NAME="Auto",DEFAULT=YES,AUTOSELECT=YES';
            $formatResultBody = str_replace($search, $defaultAudioTrackStr, $playlist);
        } else {
            $formatResultBody = $playlist;
        }
        foreach ($splitAudioMainHLSContentString as $string) {
            if (strpos( $string, '.m3u8') !== false) {
                $audioPresetFiles[] = $string;
            }
        }
        foreach ($audioPresetFiles as $file) {
            $audioTrackPresetURI = $transcodeResult->audio_hls_prefix. '/'.  $file;
            $insert = '#EXT-X-MEDIA:TYPE=AUDIO,GROUP-ID="stereo",LANGUAGE="ta",NAME="'.$transcodeResult->audio_title.'",DEFAULT=NO,AUTOSELECT=NO,URI="'.$audioTrackPresetURI.'"';
            $replace = $search. "\n". $insert;
            $formatResultBody = str_replace($search, $replace, $formatResultBody);
        }
        
        // Add AUDIO tag to each of the video preset files
        $searchTxt = '#EXT-X-STREAM-INF:PROGRAM-ID=1';
        $replaceTxt = '#EXT-X-STREAM-INF:PROGRAM-ID=1'.',AUDIO="stereo"';
        if(strpos($formatResultBody, 'AUDIO="stereo"') === false){
            $formatResultBody = str_replace($searchTxt, $replaceTxt, $formatResultBody);
        }
        $videoNewHLSFilename = $key . '/' .$this->obtainNewFilenameVideo($videoID, $videoHLSURL). '.m3u8';
        $s3Client = $this->awsRepository->awsS3Client;
        try {
            $isFileMoved = $s3Client->putObject(array(
                'Bucket' => $this->AWSBucket,
                'Key' => $videoNewHLSFilename,
                'Body' => $formatResultBody,
                'ACL' => 'public-read',
                'ServerSideEncryption' => 'AES256',
            ));
            if($isFileMoved){
                $this->deleteExistingVideoHLSFile($videoHLSFile, $videoNewHLSFilename, $videoID);
            }
        } catch (Exception $exception){
            app('log')->error(' ###File : ' . $exception->getFile() . ' ##Line : ' . $exception->getLine() . ' #Error : ' . $exception->getMessage());
        }
        $this->audioTracksModel::where('id','=',$transcodeResult->id)->update(['video_hls_update_status'=>1]);
    }
    /**
     * Method to create new name for video playlist file,
     * to clear AWS Cloudfront cache
     * 
     * @param int $videoID
     * @param string $videoHLSURL
     * 
     * @return string
     */
    public function obtainNewFilenameVideo($videoID, $videoHLSURL){
        $videoCurrentFilename = $this->getFilename($videoHLSURL);
        $splitFilename =  explode('_', $videoCurrentFilename);
        $count = (isset($splitFilename[1])) ? ($splitFilename[1] + 1) : 1;
        return ($count) ? $splitFilename[0] . '_' . ($count) : $videoCurrentFilename;
    }
    /**
     * Method to delete the videos existing HLS file and updated the same in collection
     * 
     * @param string $videoHLSFile
     * @param string $videoNewHLSFilename
     * @param int $videoID
     * 
     * @return void
     */
    public function deleteExistingVideoHLSFile($videoHLSFile, $videoNewHLSFilename, $videoID){
        $isDeleted = false;
        try{
            $isDeleted = $this->awsRepository->deleteFileFromS3Bucket($videoHLSFile);
            if($isDeleted){
                $reformedVideoURL = $this->awsBucketURL. $videoNewHLSFilename;
                $this->video->where('id','=',$videoID)->update(['hls_playlist_url' => $reformedVideoURL]);
            }
        } catch (Exception $exception){
            app('log')->error(' ###File : ' . $exception->getFile() . ' ##Line : ' . $exception->getLine() . ' #Error : ' . $exception->getMessage());
        }
    }
}