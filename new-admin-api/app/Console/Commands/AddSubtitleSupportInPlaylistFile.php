<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Contus\Video\Models\Video;
use Contus\Video\Repositories\AWSUploadRepository;
use Contus\Video\Models\TranscodedVideo;
use Contus\Video\Models\VideoPreset;
use Illuminate\Support\Facades\Log;
use Exception;

class AddSubtitleSupportInPlaylistFile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:playlist-file {video_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    protected $awsRepository = null;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(){
        parent::__construct();
        $this->video = new Video();
        $this->awsRepository = new AWSUploadRepository(new TranscodedVideo(), new VideoPreset());
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
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $videoId = $this->argument('video_id');
        Log::useDailyFiles(storage_path().'/logs/playlist-update.log');
        // Log::info([$videoId]);
        $videos = $this->video->select('aws_prefix','hls_playlist_url','id')->where('is_archived', 0)->where('job_status', 'Complete')->where('aws_prefix', 'like' , '%'."FFMPEG%")->where('hls_playlist_url', 'like' , '%'."playlist.m3u8")->get();
        foreach($videos as $videoDetails) {
            Log::info([$videoDetails->id]);

            $this->updateFile($videoDetails->aws_prefix, $videoDetails->hls_playlist_url, $videoDetails->id);        
        }
    }


    public function updateFile($key, $videoHLSURL, $videoID)
    {
        $isFileMoved = false;
        $videoCurrentFilename = $this->getFilename($videoHLSURL, true);
        $videoHLSFile = $key . '/' . $videoCurrentFilename;
        $result = $this->awsRepository->fetchFileFromS3Bucket($videoHLSFile);
        $playlist = (string) $result['Body'];
        $isNeedToUpdate = false;
        if(strpos($playlist, 'CLOSED-CAPTIONS=NONE') === false){
            $searchStrings = [
                '#EXT-X-STREAM-INF:BANDWIDTH=900000,RESOLUTION=480x360',
                '#EXT-X-STREAM-INF:BANDWIDTH=1400000,RESOLUTION=640x480',
                '#EXT-X-STREAM-INF:BANDWIDTH=2800000,RESOLUTION=1280x720',
                '#EXT-X-STREAM-INF:BANDWIDTH=5000000,RESOLUTION=1920x1080'
            ];
            foreach($searchStrings as $string) {
                if(strpos($playlist, $string) !== false) {
                    $isNeedToUpdate = true;
                    $playlist = str_replace($string, $string.',CLOSED-CAPTIONS=NONE', $playlist);
                }
            }
        } else {
            echo '3. File Not Need To Update videoId-> '.$videoID;
            return true;
        }
        

        $s3Client = $this->awsRepository->awsS3Client;
        try {
            if($isNeedToUpdate){
                $videoNewHLSFilename = $key . '/' .'playlist_new'. '.m3u8';
                $isFileMoved = $s3Client->putObject(array(
                    'Bucket' => $this->AWSBucket,
                    'Key' => $videoNewHLSFilename,
                    'Body' => $playlist,
                    'ACL' => 'public-read',
                    // 'ServerSideEncryption' => 'AES256',
                ));
                if($isFileMoved) {
                 $reformedVideoURL = $this->awsBucketURL. $videoNewHLSFilename;
                 $this->video->where('id','=',$videoID)->update(['hls_playlist_url' => $reformedVideoURL]);
                echo '1. File Need To Update and Updated videoId-> '.$videoID;
                } else {
                echo '2. File Need To Update But Not Updated videoId-> '.$videoID;
                }
            } else {
                echo '3. File Not Need To Update videoId-> '.$videoID;
            }
        } catch (Exception $exception){
            echo ' ###File : ' . $exception->getFile() . ' ##Line : ' . $exception->getLine() . ' #Error : ' . $exception->getMessage();
            app('log')->error(' ###File : ' . $exception->getFile() . ' ##Line : ' . $exception->getLine() . ' #Error : ' . $exception->getMessage());
        }
    }

    public function getFilename($sourceFile, $withEXT = null){
        $splitURL = explode('/',$sourceFile);
        $filenameArr = ($withEXT) ? end($splitURL) : (explode('.',end($splitURL)));
        return ($withEXT) ? $filenameArr : $filenameArr[0];
    }
}
