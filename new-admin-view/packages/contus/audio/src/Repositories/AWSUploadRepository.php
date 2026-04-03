<?php

/**
 * AWS Upload Repository
 *
 * To manage the functionalities related to aws upload and transcoding
 *
 * @name AWSUploadRepository
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Audio\Repositories;

use Contus\Base\Repository as BaseRepository;
use Contus\Audio\Contracts\IAWSUploadRepository;
use Aws\S3\S3Client;
use Aws\ElasticTranscoder\ElasticTranscoderClient;
use Contus\Audio\Models\TranscodedAudio;
use Contus\Audio\Models\AudioPreset;
use Contus\Audio\Models\Audios;
use Contus\Base\Helpers\StringLiterals;

class AWSUploadRepository extends BaseRepository implements IAWSUploadRepository {
    public $transcodedAudio;
    public $awsETClient;
    public $awsS3Client;
    public $AudioPreset;

    /**
     * Construct method initialization
     *
     * Validation rule for user verification code and forgot password.
     */
    public function __construct() {
        parent::__construct ();
        $this->audios = new Audios();
        $this->transcodedAudio = new TranscodedAudio();
        $this->audioPreset = new AudioPreset();
        $this->audioS3OutputFolder= config("contus.audio.audiomedia.audio.s3_location_audio_destination");
    }
    /**
     * Function to get AWS client instance.
     *
     * @return Ambigous <\Aws\static, \Aws\ElasticTranscoder\ElasticTranscoderClient>
     */
    public function getAWSClient($clientType) {
        $credentials = array (
            'region' => env('AWS_REGION'), 
            'version' => env('AWS_VERSION'), 
            'credentials' => [
                'key' => env('AWS_KEY'), 
                'secret' => env('AWS_SECRET')
            ]
        );
        if ($clientType == 'ET') {
            return ElasticTranscoderClient::factory ( $credentials );
        } else {
            return S3Client::factory ( $credentials );
        }
    }
    /**
     * Function to upload a file to S3 bucket.
     *
     * @param string $file
     * The file to be uploaded with its path.
     * @param string $bucket
     * The name of the S3 bucket.
     * @param string $key
     * The name of the output file.
     * @return boolean True on success and False on failure.
     * @see \Contus\audio\Contracts\IAWSUploadRepository::uploadFileToS3()
     */
    public function uploadFileToS3($file, $key, $type = "") {
        $permission = "";
        if($type == 'images' && $type !== ""){
            $key = $type.'/'.$key;
            $permission = 'public-read';
        }
        $info = pathinfo($file);
        $split = explode(DIRECTORY_SEPARATOR,$file);
        if(is_array($split) && count($split) >1){
            $fromName = $split[count($split)-2]; 
        }
        if($info['extension'] == "mp3" && $fromName == 'subtitle'){
          $key = 'mp3/'.$key;
          $permission = 'public-read';
        } 
        $client = $this->awsS3Client; 
        $awsS3Bucket = env('AWS_BUCKET');
        $result = $client->putObject ( array ('Bucket' => $awsS3Bucket,'Key' => $key,'folder'=>'images','SourceFile' => $file,'ServerSideEncryption' => 'AES256','ACL' => $permission ) );
        $isResult = false;
        if ($result ['ObjectURL']) {
            /**
             * Save the url of the file in S3
             */
            $isResult = $result ['ObjectURL'];
        }
        return $isResult;
    }
    /**
     * Function to upload converted files to S3 bucket.
     *
     * @param string $source
     * The source is an upload file path.
     * @param string $file
     * The file to be uploaded with its path.
     * @param string $randomFileDir
     * Which is folder name to be create into s3
     * @return string $newname.
     * The file name which is need to be create in s3
     */
    public function uploadConvertedFiles($source,$file,$randomFileDir,$newname = ''){
      $s3Client = $this->awsS3Client;
      $awsS3Bucket = env('AWS_BUCKET');
      $s3Client->putObject(array(
        'Bucket' => $awsS3Bucket,
        'SourceFile' => $source . "/" . $file,
        'Key' => 'FFMPEG/' . $randomFileDir . '/' . $file,
        'ACL' => 'public-read',
        'ServerSideEncryption' => 'AES256'
      ));
    }
    /**
     * Function to transcode a file using AWS Elastic transcoder.
     *
     * @param string $pipelineId
     * The pipeline id of the AWS Elastic Transcoder.
     * @param string $inputFile
     * The name of the input file in the S3 bucket.
     * @param string $outputSlug
     * The output slug which will be appended to the name of the output files.
     * @param integer $audioID
     * The id of the audio in the database.
     * @return string bool job id returned from the elastic transcoder on success and False on failure.
     * @see \Contus\audio\Contracts\IAWSUploadRepository::transcodeFile()
     */
    public function transcodeFile($pipelineId, $inputFile, $outputSlug, $audioID, $creatorID) {
        $client = $this->awsETClient;

        /**
         * Get active presets from the database.
         */
        $presets = $this->getActivePresets ();
        $audio = $this->audios->find ( $audioID );
        $outputs = $transcodePlaylist = array ();
        $playlistName = '';
        $outputKey = [ ];
        foreach ( $presets as $preset ) {
            $audioKey = ($preset [StringLiterals::FORMAT] == 'ts') ? 'audio-' . $preset [StringLiterals::AWS_ID] : 'audio-' . $preset [StringLiterals::AWS_ID] . '.' . $preset [StringLiterals::FORMAT];
            $output = array ('Key' => $audioKey,'Rotate' => 'auto','PresetId' => $preset [StringLiterals::AWS_ID] );

            /**
             * Check if the format is fmp4.
             * If yes, then add a playlist for the audio.
             * Playlist is mandatory for streaming formats like fmp4.
             */
            if ($preset [StringLiterals::FORMAT] == "fmp4") {
                if (strpos ( $preset ['name'], 'Smooth' ) !== false) {
                    $playlistFormat = 'Smooth';
                }
                if (strpos ( $preset ['name'], 'MPEG-Dash' ) !== false) {
                    $playlistFormat = 'MPEG-DASH';
                }
                $transcodePlaylist [] = array ('Name' => $audioKey . '-playlist','Format' => $playlistFormat,'OutputKeys' => array ($audioKey ) );
            /**
             * SegmentDuration is mandatory for fmp4 audio.
             */
            } else if ($preset [StringLiterals::FORMAT] == "ts") {
                $output ['SegmentDuration'] = '5';
                $outputKey [] = $audioKey;
            }
            $outputs [] = $output;
        }

        $playlistName = 'playlist';
        $transcodePlaylist = [ [ 'Name' => $playlistName,'Format' => 'HLSv3','OutputKeys' => $outputKey,'HlsContentProtection' => [ 'Method' => 'aes-128','KeyStoragePolicy' => 'WithVariantPlaylists' ] ] ];

        /**
         * Create a job in AWS Elastic Transcoder.
         */
        $result = $client->createJob ( array ('PipelineId' => $pipelineId,'Input' => array ('Key' => $inputFile ),'Outputs' => $outputs,'OutputKeyPrefix' => $this->audioS3OutputFolder . $outputSlug . '/','Playlists' => $transcodePlaylist ) );
        if ($result ['Job']) {
            /**
             * Save the details of the transcoded files.
             */
            
            $jobId = $result ['Job'] ['Id'];
            $awsRegion = env('AWS_REGION');
            $awsS3Bucket = env('AWS_BUCKET');
            $awsBaseUrl = env('AWS_BUCKET_URL');

            if (! empty ( $playlistName )) {
                $audioURL = $awsBaseUrl. $this->audioS3OutputFolder . $outputSlug .'/'. $playlistName . '.m3u8';
                $audio->hls_playlist_url = $audioURL;
                $audio->save();
            } else {
                foreach ( $presets as $preset ) {
                    $audioURL = 'https://s3.' . $awsRegion . '.amazonaws.com/' . $awsS3Bucket . '/'. $this->audioS3OutputFolder . $outputSlug . '/audio-' . $preset [StringLiterals::AWS_ID] . '.' . $preset [StringLiterals::FORMAT];
                    $this->transcodedaudio = new Transcodedaudio ();
                    $this->transcodedaudio->audio_id = $audioID;
                    $this->transcodedaudio->preset_id = $preset ['id'];
                    $this->transcodedaudio->audio_url = $audioURL;
                    $this->transcodedaudio->thumb_url = $thumbURL;
                    $this->transcodedaudio->is_active = 1;
                    $this->transcodedaudio->creator_id = $creatorID;
                    $this->transcodedaudio->save ();
                }
            }
            return $jobId;
        } else {
            return false;
        }
    }
    /**
     * Function to get the status of a transcode job from AWS.
     *
     * @param string $jobId
     * The id of the job whose status is to be fetched.
     * @return boolean string status if the retrieval is successful and false if not.
     */
    public function getJobStatus($jobId) {
        $client = $this->awsETClient;
        $result = $client->readJob ( array ('Id' => $jobId ) );
        if ($result ['Job']) {
            return $result ['Job'] ['Status'];
        } else {
            return false;
        }
    }
    /**
     * Function to get all active presets from the database.
     *
     * @return array All active presets from the database.
     */
    public function getActivePresets() {
        return $this->audioPreset->where ( 'is_active', 1 )->get ();
    }
    /**
     * Function to get all active presets from the database.
     *
     * @return array All active presets from the database.
     */
     public function fetchFileFromS3Bucket($key){
            $s3Client = $this->awsS3Client;
            $awsS3Bucket = env('AWS_BUCKET');
            $response = $s3Client->doesObjectExist($awsS3Bucket,$key);
            if(!$response){
            return false;
            }else {
                return $s3Client->getObject(array(
                    'Bucket' => $awsS3Bucket,
                    'Key' => $key,
                ));
            }
     }
    /**
    *Function to delete profile picture from s3bucket
    * @param string $imageName
    * The name of the image to be deleted
    * @return boolean status if deleted and false if not.
    */
    public function deleteFileFromS3Bucket($imageName){ 
        $client = $this->awsS3Client;
        $awsS3Bucket = env('AWS_BUCKET');
        $client->deleteObject(array(
            'Bucket' => $awsS3Bucket,
            'Key'    => $imageName
        ));
        return true;
   }
   /**
    * Method to fetch the transcoding progress
    *
    * @param $output
    * @return int 
    */
    public function getAWSProgressPercent($output) {
        if(!empty($output['Outputs'])) {
            $outputArray = $output['Outputs'];
            $completedCount = 0;
            $totalCount = count($outputArray);
            if($totalCount > 0) {
                foreach ($outputArray as $item) {
                  if ($item['Status'] === 'Complete') {
                    $completedCount++;
                  }
                }
            }
        }
        return round((($completedCount / $totalCount) * 100 ));
    }
}
