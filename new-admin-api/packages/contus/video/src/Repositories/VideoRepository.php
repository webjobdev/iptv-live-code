<?php

/**
 * Video Repository
 *
 * To manage the functionalities related to videos
 *
 * @name VideoRepository
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http: www.gnu.org/copyleft/gpl.html
 *
 */

namespace Contus\Video\Repositories;

use Contus\Base\Repository as BaseRepository;
use Contus\Video\Contracts\IVideoRepository;
use Contus\Base\Repositories\UploadRepository;
use Contus\Video\Models\LiveEventOrganization;
use Contus\Video\Models\PlaylistVideos;
use Contus\Video\Models\FavouritesVideos;
use Contus\Video\Models\Video;
use Contus\Video\Repositories\AWSUploadRepository;
use Contus\Video\Repositories\VideoCountriesRepository;
use Contus\Video\Repositories\VideoCastRepository;
use Contus\Video\Repositories\DashboardRepository;
use Contus\Video\Models\Category;
use Contus\Video\Models\Tag;
use Contus\Video\Models\VideoTag;
use Contus\Video\Models\individualAllowedCountries;
use Contus\Geofencing\Models\GeoCountries;
use Contus\Geofencing\Models\GeoSettings;
use Contus\Video\Models\VideoPreset;
use Carbon\Carbon;
use Contus\Base\Helpers\StringLiterals;
use Contus\Video\Models\VideoCategory;
use Contus\Video\Models\VideoSeason;
use Contus\Video\Models\VideoMetaData;
use Contus\Video\Models\CollectionVideo;
use Contus\Video\Models\VideoPoster;
use Contus\Video\Models\VideoCast;
use Contus\Video\Traits\CollectionTrait;
use Contus\Video\Models\VideoTranslation;
use Contus\Video\Repositories\LiveStreamRepository;
use Contus\Video\Models\VideoAds;
use DB;
use DateTime;
use Illuminate\Support\Facades\Auth;

class VideoRepository extends BaseRepository implements IVideoRepository
{

    use CollectionTrait;
    /**
     * class property to hold the instance of Video Model
     *
     * @var \Contus\Video\Models\Video
     */
    public $video;
    /**
     * class property to hold the instance of AWSUploadRepository
     *
     * @var \Contus\Video\Repositories\AWSUploadRepository
     */
    public $awsRepository;
    /**
     * Class property to hold the key which hold the group name requested
     *
     * @var string
     */
    protected $requestedCollection = 'q';
    /**
     * class property to hold the instance of UploadRepository
     *
     * @var \Contus\Base\Repositories\UploadRepository
     */
    public $uploadRepository;

    /**
     * Construct method initialization
     *
     * Validation rule for user verification code and forgot password.
     */
    public function __construct(AWSUploadRepository $awsRepository, UploadRepository $uploadRepository, VideoCountriesRepository $videoCountriesRepository, VideoCastRepository $videoCastRepository, CommentsRepository $commentRepository, LiveStreamRepository $livestreamrepository, DashboardRepository $dashboardRepository)
    {
        parent::__construct();

        /**
         * Set other class objects to properties of this class.
         */
        $this->video = new Video();
        $this->category = new Category();
        $this->videoPreset = new VideoPreset();
        $this->tag = new Tag();
        $this->videoTag = new VideoTag();
        $this->videoCategory = new VideoCategory();
        $this->videoSeason = new VideoSeason();
        $this->collectionVideo = new CollectionVideo();
        $this->videoAds = new videoAds();
        $this->awsRepository = $awsRepository;
        $this->uploadRepository = $uploadRepository;
        $this->videoCountriesRepository = $videoCountriesRepository;
        $this->videoCastRepository = $videoCastRepository;
        $this->commentRepository = $commentRepository;
        $this->categoryRepository = new CategoryRepository(new Category(), new UploadRepository());
        $this->livestreamRepository = $livestreamrepository;
        $this->dashboardRepository = $dashboardRepository;
        $this->setRules([
            StringLiterals::TITLE => StringLiterals::REQUIRED,
            'video_url' => StringLiterals::REQUIRED,
            'is_featured_time' => StringLiterals::REQUIRED
        ]);
    }

    /**
     * Function to add a video.
     *
     * @return boolean integer id if video is added successfully and False if not.
     */
    // public function addVideo()
    // {
    //     $typeId = null;
    //     $videoDetails = $this->request->video_details;
    //     $video = new Video ();
    //     $video->creator_id = \Auth::user()->id;

    //     $videoInfo  = explode('.', $videoDetails ['name']);
    //     $extension = strtolower(array_pop($videoInfo));
    //     if(isset($videoDetails['title']) && !empty($videoDetails['title'])) {
    //         $videoTitle = $videoDetails['title'];
    //     } else {
    //         $videoTitle = implode('.', $videoInfo);
    //     }
    //     $video->title = $videoTitle;

    //     if($extension != 'mp4' && $extension != 'mov' && $extension != 'avi' && $extension != 'mkv' ){
    //         $video->job_status = 'Convert to MP4';
    //         $video->transcode_status = 'Progressing';
    //     }else {
    //         $video->job_status = 'Video Uploaded';
    //         $video->transcode_status = 'Progressing';
    //     }
    //     // $video->is_featured = 0;
    //     $video->is_active = 0;
    //     $video->updator_id = \Auth::user()->id;
    //     $video->fine_uploader_uuid = isset($videoDetails ['uuid']) ? $videoDetails ['uuid'] : "" ;
    //     $video->video_url = isset($videoDetails ['video_url']) ? utf8_decode(urldecode($videoDetails ['video_url'])) : "" ;
    //     $video->fine_uploader_name = $string = str_replace(' ', '', $videoDetails ['name']);
    //      /**
    //      * Save the video in the database.
    //      */
    //     if ($video->save()) {
    //         $this->saveVideoCategories($video->id);
    //         return $video;
    //     }
    //     return $video;


    //     if ($video->save()) {
    //         $typeId = $video->id;
    //         /**
    //          * Associate the newly added video with uncategorized category.
    //          */
    //         $return = $typeId;
    //     } else {
    //         $return = $typeId;
    //     }
    //     return $return;
    // }

    public function addVideo()
    {
        $typeId = null;
        $videoDetails = $this->request->video_details;

        if (empty($videoDetails) || !is_array($videoDetails)) {
            return response()->json(['error' => 'Invalid video details received.'], 422);
        }

        $video = new Video();
        $video->creator_id = \Auth::user()->id;

        if (!isset($videoDetails['name'])) {
            return response()->json(['error' => 'Video name is missing.'], 422);
        }

        $videoInfo = explode('.', $videoDetails['name']);
        $extension = strtolower(array_pop($videoInfo));

        $videoTitle = isset($videoDetails['title']) && !empty($videoDetails['title'])
            ? $videoDetails['title']
            : implode('.', $videoInfo);

        $video->title = $videoTitle;

        if (!in_array($extension, ['mp4', 'mov', 'avi', 'mkv'])) {
            $video->job_status = 'Convert to MP4';
            $video->transcode_status = 'Progressing';
        } else {
            $video->job_status = 'Video Uploaded';
            $video->transcode_status = 'Progressing';
        }

        $video->is_active = 0;
        $video->updator_id = \Auth::user()->id;
        $video->fine_uploader_uuid = $videoDetails['uuid'] ?? "";
        $video->video_url = isset($videoDetails['video_url']) ? utf8_decode(urldecode($videoDetails['video_url'])) : "";
        $video->fine_uploader_name = str_replace(' ', '', $videoDetails['name']);

        if ($video->save()) {
            $this->saveVideoCategories($video->id);
            return $video;
        }

        return response()->json(['error' => 'Failed to save video.'], 500);
    }

    public function add()
    {
        $video = new Video();
        $video->creator_id = Auth::user()->id;
        $videoDetails = $this->request->video_details;

        $video->updator_id = Auth::user()->id;
        $video->fine_uploader_uuid = $videoDetails['uuid'] ?? "";
        $video->video_url = $videoDetails['video_url'] ?? "";
        $video->fine_uploader_name = str_replace(' ', '', $videoDetails['name'] ?? '');

        if ($video->save()) {
            $this->saveVideoCategories($video->id);
            return response()->json($video);
        }

        return response()->json(['error' => 'Failed to save video'], 500);
    }


    /**
     * Function to update a upload the video.
     *
     * @param integer $id
     * The id of the video.
     * @return boolean True if video updated successfully and False if not.
     */

    public function editVideo($id)
    {
        if (!empty($id)) {
            /**
             * Replace the video based on video id.
             */
            $video = $this->video->findorfail($id);
            $video->title = $this->request->title;
            $video->video_url = isset($this->request->video_url) ? utf8_decode(urldecode($this->request->video_url)) : "";
            $videoInfo = explode('.', $this->request->newVideoName);
            $extension = strtolower(array_pop($videoInfo));
            if ($extension != 'mp4' && $extension != 'mov' && $extension != 'avi' && $extension != 'mkv') {
                $video->job_status = 'Convert to MP4';
                $video->transcode_status = 'Progressing';
            } else {
                $video->job_status = 'Video Uploaded';
                $video->transcode_status = 'Progressing';
            }

            $video->fine_uploader_uuid = $this->request->newVideoUUID;
            $video->fine_uploader_name = str_replace(' ', '', $this->request->newVideoName);
            $video->upload_percentage = 0;
            $video->updated_at = new \DateTime();
            $video->save();
            return true;
        } else {
            return false;
        }
    }
    public function getGeoInfo($id = null)
    {
        // $videoIdArray = explode(',', base64_decode($id));
        $videoIdArray = $id;
        $settingsType = GeoSettings::where('is_active', 1)->first();
        if ($settingsType['type'] == 'individual_allowed_countries' && $id != "null") {
            $selectedCountries = individualAllowedCountries::where('video_id', $videoIdArray)->get();
        } else {
            $selectedCountries = null;
        }
        return $selectedCountries;
    }
    /**
     * Function to update a video.
     *
     * @param integer $id
     * The id of the video.
     * @return boolean True if video updated successfully and False if not.
     */
    public function updateVideo($id)
    {
        // dd("check this function:");
        /**
         * Check if the video id is not empty.
         */
        if (!empty($id)) {
            /**
             * Set validation rules for edit functionality.
             */
            if ($this->request->geoType == 'individual_allowed_countries') {
                individualAllowedCountries::where('video_id', $id)->delete();
                foreach ($this->request->allowedData as $key => $value) {
                    $regions = [];
                    $allowedCountries = new individualAllowedCountries();
                    $countryName = Geocountries::where('short_code', $key)->first();
                    $allowedCountries->country_id = $countryName['id'];
                    $allowedCountries->country_name = $countryName['country_name'];
                    $allowedCountries->country_short_code = $key;
                    $allowedCountries->video_id = $id;
                    foreach ($value as $region) {
                        $regions[] = $region['short_code'];
                    }
                    $allowedCountries->regions = $regions;
                    $allowedCountries->save();
                }
            }
            $video = $this->video->findorfail($id);
            if ($video->is_live === 1) {
                $this->setRules([
                    StringLiterals::TITLE => StringLiterals::REQUIRED,
                    // 'description' => StringLiterals::REQUIRED,
                    'hls' => 'required|url',
                    'category' => 'required',
                ]);
            } elseif ($video->is_live === 2) {
                $this->setRules([
                    StringLiterals::TITLE => StringLiterals::REQUIRED,
                    // 'description' => StringLiterals::REQUIRED,
                    'hls' => 'required|url',
                    'category' => 'required',
                ]);
            } elseif ($video->is_live === 3) {
                $this->setRules([
                    StringLiterals::TITLE => StringLiterals::REQUIRED,
                    // 'description' => StringLiterals::REQUIRED,
                    'hls' => 'required|url',
                    // 'category' => 'required',
                    'scheduled_time' => 'required',
                    'expire_scheduled_time' => 'required',
                ]);
            } else {
                if (request()->is_kids === true) {
                    $this->setRules([
                        StringLiterals::TITLE => StringLiterals::REQUIRED,
                        // 'category' => 'required',
                        // 'description' => StringLiterals::REQUIRED,
                        'season' => 'required_if:is_webseries,true',
                        'release_year' => 'required|integer|min:' . (date("Y") - 220) . '|max:' . date("Y"),
                        // 'age_limit' => 'required_if:is_parental,1',
                    ]);
                } else {
                    $this->setRules([
                        StringLiterals::TITLE => StringLiterals::REQUIRED,
                        'category' => 'required',
                        // 'description' => StringLiterals::REQUIRED,
                        'season' => 'required_if:is_webseries,true',
                        'release_year' => 'required|integer|min:' . (date("Y") - 220) . '|max:' . date("Y"),
                        // 'age_limit' => 'required_if:is_parental,1',
                    ]);
                }
                // $this->setMessages('age_limit.required', "Age limt required");

            }

            $this->validate($this->request, $this->getRules());
            $bucketName = config("contus.base.base.bucketname");
            $video->title = $this->request->title;
            $video->title_two = $this->request->title_two;
            $video->description = $this->request->description;
            $video->is_active = $this->request->is_active ? 1 : 0;
            $video->is_kids = $this->request->is_kids ? 1 : 0;
            $video->is_notify = $this->request->is_notify ? 1 : 0;
            $video->hide_web = $this->request->hide_web ? 1 : 0;
            $video->is_notified = $this->request->is_notify ? $video->is_notified : 0;
            // $video->is_premium = $this->request->is_premium ? 1 : 0;
            $video->release_year = $this->request->release_year;
            $video->content_sets = $this->request->content_sets;
            $video->is_parental = $this->request->is_parental;
            $video->age_limit = $this->request->age_limit;
            $video->video_quality = $this->request->video_quality;
            $video->is_webseries = $this->request->is_webseries ? 1 : 0;
            $video->episode_order = $this->request->episode_order;
            $video->playback_token = $this->request->playback_token;
            $video->policy = $this->request->policy;
            $video->price = $this->request->price ? $this->request->price : '0.00';

            if ($video->is_live === 2 || $video->is_live === 1 || $video->is_live === 3) {
                $video->hls_playlist_url = $this->request->hls;
            }

            $tmrtime = strtotime($this->request->created_at) + 86400;
            $tmrdat = date('Y-m-d H:i:s', $tmrtime);

            $currentDate = Carbon::now();
            $startDate = $this->request->created_at;
            $endDate = $tmrdat;

            if ($currentDate > $startDate && $currentDate < $endDate) {
                //\Log::info('Between');
                $video->is_premium = 1;
            } else {
                //\Log::info('In Not Between'); 
                $video->is_premium = 0;
            }

            if ($this->request->has('published_on')) {
                $video->published_on = Carbon::parse($this->request->published_on);
            }

            // NOTE : To format the scheduled time into db format


            $date = ($this->request->has('scheduled_time')) ? $this->request->scheduled_time : '';
            $expire_scheduled_time = ($this->request->has('expire_scheduled_time')) ? $this->request->expire_scheduled_time : '';

            $video->scheduledStartTime = $date; //format_schedule_date($date);
            $video->scheduledEndTime = $expire_scheduled_time;


            // $date = ($this->request->has('scheduled_time')) ? $this->request->scheduled_time : '';
            // $scheduledStartTime= Carbon::parse($date)->format('Y-m-d H:i:s'); 

            // $expire_scheduled_time = ($this->request->has('expire_scheduled_time')) ? $this->request->expire_scheduled_time : '';
            // $scheduledEndTime= Carbon::parse($expire_scheduled_time)->format('Y-m-d H:i:s'); 


            // $video->scheduledStartTime = $scheduledStartTime; //format_schedule_date($date);
            // $video->scheduledEndTime = $scheduledEndTime;


            $video->updator_id = \Auth::user()->id;
            if ($this->request->has('trailer_updated') && ($this->request->trailer_updated == 1)) {
                $this->deleteVideoTrailer($id);
                $video->trailer_url = $this->request->trailer_url;
                $video->trailer_status = 'Trailer Uploaded';
                $video->trailer_hls_url = '';
                $video->trailer_hls_prefix = '';
                $video->trailer_jobid = '';
            } else if ($this->request->has('trailer_updated') && ($this->request->trailer_updated == 0)) {
                $this->deleteVideoTrailer($id);
                $video->trailer_url = $this->request->trailer_url;
                $video->trailer_status = '';
                $video->trailer_hls_url = '';
                $video->trailer_hls_prefix = '';
                $video->trailer_jobid = '';
            }
            $video->video_order = (int) $this->request->video_order;
            if ($this->request->has('presenter')) {
                $video->presenter = $this->request->presenter;
            }
            if ($this->request->has('search_tag')) {
                $this->videoTag->where(StringLiterals::VIDEOID, $id)->delete();
                foreach ($this->request->search_tag as $value) {
                    $tagInfo = $this->tag->where('name', $value['text'])->first();
                    if (empty($tagInfo)) {
                        $tagInfo = new Tag();
                        $tagInfo->name = $value['text'];
                        $tagInfo->save();
                    }
                    $tagInfo->videos()->attach($id);
                }
            }
            if ($this->request->thumbnail && $this->request->is_thumbnail_updated == 1) {
                $this->deleteVideoImages($id, 'thumbnail');
                $fileName = $video->getImageBaseName($this->request->thumbnail);
                // $folderName = config("contus.base.image.thumbnail.s3_location");
                // $localStoragePath = public_path() . DIRECTORY_SEPARATOR . config("contus.base.image.thumbnail.temporary_image_storage_path");
                $localStoragePath = config("app.url") . config("contus.base.image.thumbnail.temporary_image_storage_path");
                // $s3BucketImgFilename = $this->uploadTos3Bucket($fileName, $folderName, $localStoragePath);
                $s3BucketImgURL = $localStoragePath . DIRECTORY_SEPARATOR . $fileName;
                $video->thumbnail_image = $s3BucketImgURL;
            } else {
                if ($this->request->thumbnail) {
                    $thumbImgurl = parse_url($this->request->thumbnail);
                    $video->thumbnail_image = ltrim(str_ireplace($bucketName, '', $thumbImgurl['path']), '/');
                }
            }
            if ($this->request->poster_image && $this->request->is_posterimg_updated == 1) {
                $this->deleteVideoImages($id, 'poster');
                $fileName = $video->getImageBaseName($this->request->poster_image);
                // $folderName = config("contus.base.image.posters.s3_location");
                // $localStoragePath = public_path() . DIRECTORY_SEPARATOR . config("contus.base.image.posters.temporary_image_storage_path");
                $localStoragePath = config("app.url") . config("contus.base.image.posters.temporary_image_storage_path");
                // $s3BucketImgFilename = $this->uploadTos3Bucket($fileName, $folderName, $localStoragePath);
                $s3BucketImgURL = $localStoragePath . DIRECTORY_SEPARATOR . $fileName;
                $video->poster_image = $s3BucketImgURL;
            } else {
                if ($this->request->poster_image) {
                    $posterImgurl = parse_url($this->request->poster_image);
                    $video->poster_image = ltrim(str_ireplace($bucketName, '', $posterImgurl['path']), '/');
                }
            }

            //mobile poser Image
            if ($this->request->mobile_poster_image && $this->request->is_mobile_posterimg_updated == 1) {
                $this->deleteVideoImages($id, 'mobile_poster_image');
                $fileName = $video->getImageBaseName($this->request->mobile_poster_image);
                // $folderName = config("contus.base.image.posters.s3_location");
                // $localStoragePath = public_path() . DIRECTORY_SEPARATOR . config("contus.base.image.posters.temporary_image_storage_path");
                $localStoragePath = config("app.url") . config("contus.base.image.posters.temporary_image_storage_path");
                // $s3BucketImgFilename = $this->uploadTos3Bucket($fileName, $folderName, $localStoragePath);
                $s3BucketImgURL = $localStoragePath . DIRECTORY_SEPARATOR . $fileName;
                $video->mobile_poster_image = $s3BucketImgURL;
            } else {
                if ($this->request->mobile_poster_image) {
                    $posterImgurl = parse_url($this->request->mobile_poster_image);
                    $video->mobile_poster_image = ltrim(str_ireplace($bucketName, '', $posterImgurl['path']), '/');
                }
            }


            $video->audio_language = $this->request->audio_language;
            $isVideo = false;

            $video->updated_at = new \DateTime();
            /**
             * Update the video details in the data base.
             */
            if ($video->save()) {
                $this->saveVideoCategories($video->id);
                $this->saveVideoIntoElastic($video->id);
                $isVideo = true;
            }

            $user = Auth::user();

            foreach ($this->request->organization as $orgId) {
                LiveEventOrganization::updateOrCreate([
                    'live_event_id' => $video->id,
                    'organization_id' => $orgId
                ], [
                    'created_by' => $user->id
                ]);
            }
            return $isVideo;
        } else {
            return false;
        }
    }
    /**
     * Repository function to add language for video
     * The id of the video
     * @return boolean True if add language is successfully and false if not
     */
    public function updateVideoLanguage($id)
    {
        if (!empty($id)) {
            $this->setRules([
                StringLiterals::TITLE => StringLiterals::REQUIRED,
                'description' => StringLiterals::REQUIRED,
                'languageCode' => StringLiterals::REQUIRED,
            ]);
            $this->validate($this->request, $this->getRules());
            $video_tramslation = null;
            if (VideoTranslation::where('video_id', '=', $id)->where('language_id', '=', $this->request->languageCode)->count() > 0) {
                $video_tramslation = VideoTranslation::where('video_id', '=', $id)->where('language_id', '=', $this->request->languageCode)->first();
            } else {
                $video_tramslation = new VideoTranslation;
                $video_tramslation->video_id = $id;
                $video_tramslation->language_id = $this->request->languageCode;
            }
            $video_tramslation->title = $this->request->title;
            $video_tramslation->title_two = $this->request->title_two ? $this->request->title_two : '';
            $video_tramslation->description = $this->request->description;
            $video_tramslation->presenter = ($this->request->has('presenter')) ? $this->request->presenter : '';
            if ($video_tramslation->save()) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function updateMultipleVideoLanguage($id)
    {

        if (!empty($id)) {
            $this->setRules([
                StringLiterals::TITLE => StringLiterals::REQUIRED,
                'description' => StringLiterals::REQUIRED,
                'languageCode' => StringLiterals::REQUIRED,
            ]);

            $this->validate($this->request, $this->getRules());
            $video_tramslation;
            if (VideoTranslation::where('video_id', '=', $id)->where('language_id', '=', $this->request['languageCode'])->count() > 0) {
                $video_tramslation = VideoTranslation::where('video_id', '=', $id)->where('language_id', '=', $this->request['languageCode'])->first();
            } else {
                $video_tramslation = new VideoTranslation;
                $video_tramslation->video_id = $id;
                $video_tramslation->language_id = $this->request['languageCode'];
            }
            $video_tramslation->title = $this->request[0]['title'];
            $video_tramslation->title_two = $this->request[0]['title_two'] ? $this->request[0]['title_two'] : '';
            $video_tramslation->description = $this->request[0]['description'];
            $video_tramslation->presenter = $this->request[0]['presenter'];
            if ($video_tramslation->save()) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Repository function to delete video thumbnail/poster images.
     *
     * @param integer $id
     * @param string $type poster or thumbnail
     * @return boolean True if the thumbnail is deleted and false if not.
     */
    public function deleteVideoImages($id, $type)
    {
        /**
         * Check if category id exists.
         */
        if (!empty($id)) {
            $video = $this->video->findorfail($id);
            $imageData = ($type == 'thumbnail') ? $video->thumbnail_image : $video->poster_image;
            if ($type == 'mobile_poster') {
                $imageData = $video->mobile_poster_image;
            }

            if (!empty($imageData)) {
                $URL = $video->getImageBaseNameWithDirectory($imageData);
                /** call to method to delete image in S3 bucket */
                $imageURL = ($type == 'thumbnail') ? config("contus.base.image.thumbnail.s3_fetch_location") . $URL
                    : config("contus.base.image.posters.s3_fetch_location") . $URL;
                $this->uploadRepository->deleteFileFromS3Bucket($imageURL);
                /** Process to delete image from local storage path */
                $localFilePath = ($type == 'thumbnail') ? public_path() . DIRECTORY_SEPARATOR . config("contus.base.image.thumbnail.temporary_image_storage_path")
                    : public_path() . DIRECTORY_SEPARATOR . config("contus.base.image.posters.temporary_image_storage_path");
                $this->uploadRepository->deleteImageFileInLocalPath($localFilePath, $URL);
                /**
                 * Empty the image_url and image_path field in the database.
                 */
                ($type == 'thumbnail') ? $video->thumbnail_image = '' : $video->poster_image = '';
                $video->save();
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    /**
     * Function to save categories of a video in the database.
     *
     * @param integer $id
     * The id of the video whose categories are being saved.
     */
    public function saveVideoCategories($id)
    {
        $this->videoCategory = new VideoCategory();
        $this->videoCategory->where(StringLiterals::VIDEOID, $id)->delete();
        $this->videoSeason->where(StringLiterals::VIDEOID, $id)->delete();
        $this->collectionVideo->where(StringLiterals::VIDEOID, $id)->delete();
        $this->videoAds->where(StringLiterals::VIDEOID, $id)->delete();

        if ($this->request->has('category') && !empty($this->request->category)) {

            if ($this->request->is_webseries) {
                $categoryId = $this->request->category;
                $this->videoCategory = new VideoCategory();
                $this->videoCategory->video_id = $id;
                $this->videoCategory->category_id = (int) $categoryId;
                $this->videoCategory->save();
            } else {

                if ($this->request->is_live_value === 1) {
                    // $categoryId = $this->request->category;
                    // $this->videoCategory = new VideoCategory ();
                    // $this->videoCategory->video_id = $id;
                    // $this->videoCategory->category_id = (int)$categoryId;
                    // $this->videoCategory->save();
                    $cat = $this->request->category;
                    $arrlength = count($cat);
                    for ($x = 0; $x < $arrlength; $x++) {
                        $categoryId = $this->request->category;
                        $this->videoCategory = new VideoCategory();
                        $this->videoCategory->video_id = $id;
                        $this->videoCategory->category_id = $cat[$x];
                        $this->videoCategory->save();
                    }
                } else {
                    $cat = $this->request->category;
                    $arrlength = count($cat);
                    for ($x = 0; $x < $arrlength; $x++) {
                        $categoryId = $this->request->category;
                        $this->videoCategory = new VideoCategory();
                        $this->videoCategory->video_id = $id;
                        $this->videoCategory->category_id = $cat[$x];
                        $this->videoCategory->save();
                    }
                }
            }
        }

        if ($this->request->has('group') && !empty($this->request->group)) {
            if ($this->request->is_webseries) {
                $group = $this->request->group;
                $category = $this->request->category;
                $category = Category::find($category);
                $this->collectionVideo = new CollectionVideo();
                $this->collectionVideo->video_id = $id;
                $this->collectionVideo->group_id = (int) $group;
                $this->collectionVideo->parent_cateogry_id = $category->parent_id;
                $this->collectionVideo->save();
            } else {
                $group = $this->request->group;
                $category = $this->request->category;
                $category = Category::find($category);
                $categorylength = count($category);
                $grouplength = count($group);
                for ($x = 0; $x < $grouplength; $x++) {
                    for ($y = 0; $y < $categorylength; $y++) {
                        $collection = CollectionVideo::where('video_id', $id)->where('group_id', $group[$x])->where('parent_cateogry_id', $category[$y]->parent_id)->first();
                        if ($collection == null) {
                            $collection = new CollectionVideo();
                        }
                        $collection->video_id = $id;
                        $collection->group_id = $group[$x];
                        $collection->parent_cateogry_id = $category[$y]->parent_id;
                        $collection->save();
                    }
                }
            }
        }



        if ($this->request->has('season') && !empty($this->request->season)) {
            $seasonId = $this->request->season;
            $this->videoSeason = new VideoSeason();
            $this->videoSeason->video_id = $id;
            $this->videoSeason->season_id = $seasonId;
            $this->videoSeason->save();
        }

        if ($this->request->has('ads') && !empty($this->request->ads)) {
            $adsId = $this->request->ads;
            $this->videoAds = new VideoAds();
            $this->videoAds->video_id = $id;
            $this->videoAds->ads_id = $adsId;
            $this->videoAds->save();
        }
    }

    /**
     * Function to get validation rules for video edit form.
     *
     * @return array The validation rules.
     */
    public function getVideoEditRules()
    {
        /**
         * Set rules for video edit feature.
         */
        $this->setRules([
            StringLiterals::TITLE => StringLiterals::REQUIRED,
            'category' => StringLiterals::REQUIRED,
            'description' => StringLiterals::REQUIRED,
            StringLiterals::ISACTIVE => StringLiterals::REQUIREDINTEGER,
        ]);

        return $this->getRules();
    }

    /**
     * Function to get validation rules for video thumb upload form.
     *
     * @return array The validation rules.
     */
    public function getThumbUploadRules()
    {
        /**
         * Set rules for thumbnail upload feature.
         */
        $this->setRules([StringLiterals::THUMBNAIL => StringLiterals::REQUIRED]);
        return $this->getRules();
    }

    /**
     * Function to update thumbnail of a video.
     *
     * @param integer $id
     * The id of the video
     * @return boolean True if uploaded successfully and false if not.
     */
    public function updateThumbnail($id)
    {
        /**
         * Check if the video id for the thumbnail is not empty.
         */
        if (!empty($id)) {
            /**
             * Set the validation rules for the thumbnail.
             */
            $this->setRules([StringLiterals::THUMBNAIL => StringLiterals::REQUIRED]);
            /**
             * Perform validation for the thumbnail upload.
             */
            $this->validate($this->request, $this->getRules());

            $this->uploadRepository->setModelIdentifier(UploadRepository::MODEL_IDENTIFIER_THUMBNAIL)->setRequestParamKey(StringLiterals::THUMBNAIL)->setConfig();

            $video = $this->video->findorfail($id);
            if ($this->request->has(StringLiterals::THUMBNAIL)) {
                /**
                 * Upload the thumbnail.
                 */
                $this->uploadRepository->handleUpload($video);
                $isVideo = true;
            } else {
                $isVideo = false;
            }
            return $isVideo;
        } else {
            return false;
        }
    }

    /**
     * Function to archive videos in the database.
     * This function works like a soft delete and the video files in AWS S3 are not deleted.
     *
     * @param integer|array $ids
     * The ids of the videos which are to be deleted.
     * @return boolean True if the videos are archived successfully and false if not.
     */
    public function videoDelete($ids)
    {
        /**
         * Delete the video by the given id
         */
        $ids = is_array($ids) ? $ids : [$ids];
        $status = false;

        if (!empty($ids)) {
            $videoDetails = array();
            $videoDetails['is_archived'] = 1;
            $endpoint = 'updatemultipledocs';
            $data = ['updateFields' => $videoDetails, 'ids' => $ids];
            $this->callElasticsearchService('POST', $endpoint, $data);
            foreach ($ids as $id) {
                PlaylistVideos::where('video_id', (string) $id)->delete();
                FavouritesVideos::where('video_id', (int) $id)->delete();
            }

            $this->video->whereIn('id', $ids)->update([StringLiterals::IS_ARCHIVED => 1, 'archived_on' => Carbon::now()]);
            app('cache')->tags('videos')->flush();
            $this->livestreamRepository->deleteLivestream($ids);
            $status = true;
            ;
        }
        return $status;
    }

    /**
     * Function to activate the videos
     *
     * @param integer|array $ids
     * The ids of the videos which are to be activated.
     * @return boolean True if the videos are archived successfully and false if not.
     */
    // public function videoActivateOrDeactivate($ids, $isStatus){
    //    /**
    //     * Delete the video by the given id
    //     */
    //    $ids = is_array($ids) ? $ids : [$ids];
    //    if(!empty ($ids)){
    //     $videoDetails  =array();
    //     $videoDetails['is_active'] = ($isStatus == 'activate') ? 1 : 0;
    //     $data = ['updateFields' => $videoDetails, 'ids' => $ids];
    //     $endpoint = 'updatemultipledocs';
    //     $this->callElasticsearchService('POST', $endpoint, $data);
    // }

    public function videoActivateOrDeactivate($ids, $isStatus)
    {
        /**
         * Delete the video by the given id
         */
        $ids = is_array($ids) ? $ids : [$ids];
        if (!empty($ids)) {
            $videoDetails = array();
            $videoDetails['is_active'] = ($isStatus == 'activate') ? 1 : 0;
            $data = ['updateFields' => $videoDetails, 'ids' => $ids];
            foreach ($ids as $id) {
                PlaylistVideos::where('video_id', (string) $id)->update(['is_active' => $isStatus]);
                FavouritesVideos::where('video_id', (int) $id)->update(['is_active' => $isStatus]);
            }
            $endpoint = 'updatemultipledocs';
            $this->callElasticsearchService('POST', $endpoint, $data);
        }



        /**
         * Check if the status is activate.
         * If yes, set is_active field to 1.
         * If no, then set is_active field to 0.
         */
        if ($isStatus == 'activate') {
            $status = empty($ids) ? StringLiterals::LITERALFALSE : $this->video->whereIn('id', $ids)->update([StringLiterals::ISACTIVE => 1]);
            return $status;
        } else if ($isStatus == 'deactivate') {
            $status = empty($ids) ? StringLiterals::LITERALFALSE : $this->video->whereIn('id', $ids)->update([StringLiterals::ISACTIVE => 0]);
            return $status;
        }
    }

    /**
     * Prepare the grid
     * set the grid model and relation model to be loaded
     *
     * @vendor Contus
     *
     * @package Collection
     * @return Contus\Collection\Repositories\BaseRepository
     */
    public function prepareGrid()
    {
        /**
         * To load the data in to the grid depands up on request
         */

        $this->setGridModel($this->video)->setEagerLoadingModels(['videocategory.category', 'collections']);
        return $this;
    }

    /**
     * Get headings for grid
     *
     * @vendor Contus
     *
     * @package Collection
     * @return array
     */
    public function getGridHeadings()
    {
        $filters = $this->request->input('filters');

        $checkLive = false;
        if (!empty($filters)) {
            foreach ($filters as $value) {
                if ($value == 'live_videos') {
                    $checkLive = true;
                }
            }
        }
        if ($checkLive) {
            return [
                StringLiterals::GRIDHEADING => [
                    ['name' => trans('video::videos.title'), StringLiterals::VALUE => StringLiterals::TITLE, 'sort' => true],
                    ['name' => trans('video::videos.status'), StringLiterals::VALUE => StringLiterals::ISACTIVE, 'sort' => false],
                    ['name' => 'Type', StringLiterals::VALUE => StringLiterals::ISACTIVE, 'sort' => false],
                    ['name' => trans('video::videos.status'), StringLiterals::VALUE => 'liveStatus', 'sort' => false],
                    ['name' => trans('video::videos.scheduled_on'), StringLiterals::VALUE => '', 'sort' => false],
                    ['name' => trans('video::videos.added_on'), StringLiterals::VALUE => '', 'sort' => false],
                    ['name' => trans('video::videos.action'), StringLiterals::VALUE => '', 'sort' => false]
                ]
            ];
        } else {
            return [
                'heading' => [
                    ['name' => 'S.No', 'S.No' => 'SNo', 'sort' => false],
                    ['name' => trans('video::videos.title'), trans('video::videos.value') => 'title', 'sort' => true],
                    //['name' => trans('video::videos.video_type'), trans('video::videos.value') => '', 'sort' => false],
                    ['name' => trans('video::videos.upload_status'), trans('video::videos.value') => 'job_status', 'sort' => false],
                    ['name' => trans('video::videos.status'), trans('video::videos.value') => 'is_active', 'sort' => false],
                    ['name' => trans('video::videos.category'), trans('video::videos.value') => 'category_id', 'sort' => false],
                    ['name' => 'Genre', 'Genre' => 'group_id', 'sort' => false],
                    // ['name' => trans('video::videos.price'), trans('video::videos.value') => 'price', 'sort' => true],
                    //['name' => trans('video::videos.schedule'), trans('video::videos.value') => 'scheduledStartTime', 'sort' => false],
                    ['name' => trans('video::videos.views'), trans('video::videos.value') => 'view_count', 'sort' => true],
                    // ['name' => trans('video::videos.like'), trans('video::videos.value') => 'like_count', 'sort' => false],
                    // ['name' => trans('video::videos.dislike'), trans('video::videos.value') => 'dislike_count', 'sort' => false],
                    // ['name' => trans('video::videos.favourite'), trans('video::videos.value') => 'favourite_count', 'sort' => false],
                    // ['name' => trans('video::videos.comment'), trans('video::videos.value') => 'comments_count', 'sort' => false],
                    // ['name' => trans('video::videos.uploaded_on'), trans('video::videos.value') => 'created_at', 'sort' => false],
                    // ['name' => trans('video::videos.uploaded_by'), trans('video::videos.value') => '', 'sort' => false],
                    // ['name' => trans('video::videos.published_on'), trans('video::videos.value') => 'published_on', 'sort' => false],
                    ['name' => trans('video::videos.action'), trans('video::videos.value') => '', 'sort' => false]
                ]
            ];
        }
    }

    /**
     * update grid records collection query
     *
     * @param mixed $builder
     * @return mixed
     */
    protected function updateGridQuery($builder)
    {
        /**
         * updated the grid query by using this function and apply the video condition.
         */

        return $builder->selectRaw('videos.*, videos.id as favourite_count, videos.id as comments_count,  videos.id as like_count, videos.id as dislike_count, videos.id as is_show_hide_comment, videos.id as is_show_hide_likes, videos.id as formatted_created_date, videos.id as formatted_published_date,videos.id as is_banner')->groupBy('videos.id')->with(['categories', 'user'])->where(StringLiterals::IS_ARCHIVED, 0)->where('is_live', 0);
    }

    /**
     * Function to apply filter for search of videos grid
     *
     * @param mixed $builderVideos
     * @return \Illuminate\Database\Eloquent\Builder $builderVideos The builder object of videos grid.
     */
    protected function searchFilter($builderVideos)
    {

        $searchRecordVideos = $this->request->has(StringLiterals::SEARCHRECORD) && is_array($this->request->input(StringLiterals::SEARCHRECORD)) ? $this->request->input(StringLiterals::SEARCHRECORD) : [];
        $title = $is_active = $type = $video_type = $category = $genre = null;
        extract($searchRecordVideos);

        /**
         * Check if the title of the video is present in the video search.
         * If yes, then use it in filter.
         */
        if ($title) {
            $builderVideos = $builderVideos->where(StringLiterals::TITLE, 'like', '%' . $title . '%');
        }
        /**
         * Check if the title of the category is present in the video search.
         * If yes, then use it in filter.
         */
        if (!empty($category)) {
            $builderVideos = $builderVideos->whereHas('categories', function ($query) use ($category) {
                $query->whereIn('categories.id', $category);
            });
        }

        if ($genre) {
            $builderVideos = $builderVideos->whereHas('genrecollections', function ($query) use ($genre) {
                $query->where('groups.name', 'like', '%' . $genre . '%');
            });
        }

        /**
         * Check if the status of the video is present in the video search.
         * If yes, then use it in filter.
         */
        if (is_numeric($is_active)) {
            $builderVideos = $builderVideos->where(StringLiterals::ISACTIVE, $is_active);
        }
        /**
         * Check if the type of the video is present in the video search.
         * If yes, then use it in filter.
         */
        if ($type == "wowza") {
            $builderVideos = $builderVideos->where(StringLiterals::USERNAME, $type);
        } else if ($type != null && $type != 'all') {
            $builderVideos = $builderVideos->where(StringLiterals::YOUTUBE_PRIVACY, $type);
        }

        /**
         * Check if the type of the video in the video search.
         * If yes, then use it in filter.
         */
        if (is_numeric($video_type) && $video_type == 2) {
            $builderVideos = $builderVideos->where('scheduledStartTime', '!=', '');
        } else if (is_numeric($video_type)) {
            $builderVideos = $builderVideos->where('is_live', $video_type);
        }




        return $builderVideos;
    }

    /**
     * Fetch video to edit.
     *
     * @vendor Contus
     *
     * @package Video
     * @return response
     */
    public function getVideo($id)
    {
        $videoIdArray = explode(',', base64_decode($id));
        return $this->video->with('categories', 'tags', 'collections', 'seasons', 'videoTranslation', 'ads', 'cast', 'videoAudioTracks', 'getAllOrganization')
            ->whereIn('id', $videoIdArray)
            ->where(StringLiterals::IS_ARCHIVED, 0)->get();
    }
    public function getVideoId($id)
    {
        $videoIdArray = explode(',', base64_decode($id));
        return count($videoIdArray);
    }

    /**
     * Function to fetch all the details of a video from the database.
     *
     * @param integer $id
     * The id of the video whose data are to be fetched.
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|NULL The information of the video.
     */
    public function getCompleteVideoDetails($id)
    {
        $this->video = $this->video->selectRaw('videos.*, videos.id as favourite_count, videos.id as comments_count, videos.id as like_count, videos.id as dislike_count, videos.id as is_show_hide_comment, videos.id as is_show_hide_likes, videos.id as formatted_created_date, videos.id as formatted_updated_date, videos.id as formatted_published_date')->groupBy('videos.id')->with(['tags', 'categories.parent_category.parent_category', 'collections', 'user', 'comments', 'videoMetaData'])->where('id', $id)->where(StringLiterals::IS_ARCHIVED, 0)->first();
        $this->video->recent = $this->video->recent()->get()->count();
        $this->video->authfavourites = $this->video->authfavourites()->get()->count();
        $this->video->performanceStatistics = $this->dashboardRepository->getPerformanceSubscribeAnalytics($id, 4);
        $this->video->geographicStatistics = $this->dashboardRepository->getGeographicAnalytics($id, 4);
        $this->video->chart_date_filter = $this->dashboardRepository->chartDateFilter();



        return $this->video;
    }

    /**
     * Repository function to delete poster of a video.
     *
     * @param integer $id
     * The id of the poster.
     * @return boolean True if the poster is deleted and false if not.
     */
    public function deletePoster($id)
    {
        /**
         * Check if poster id exists.
         */
        if (!empty($id)) {
            $videoPoster = VideoPoster::findorfail($id);
            /**
             * Delete the poster image using the image path field from the database.
             */
            if (file_exists($videoPoster->image_path) && unlink($videoPoster->image_path)) {
                /**
                 * Delete the poster in the database.
                 */
                $videoPoster->delete();
                $deleteStatus = true;
            } else {
                $deleteStatus = false;
            }
            return $deleteStatus;
        } else {
            return false;
        }
    }

    /**
     * Repository function to delete cast image of a video.
     *
     * @param integer $id
     * The id of the cast image.
     * @return boolean True if the cast image is deleted and false if not.
     */
    public function deleteCastImage($id)
    {
        /**
         * Check if cast id exists.
         */
        if (!empty($id)) {
            $videoCast = VideoCast::findorfail($id);
            /**
             * Delete the cast image using the image path field from the database.
             */
            if (file_exists($videoCast->image_path) && unlink($videoCast->image_path)) {
                /**
                 * Delete the cast image in the database.
                 */
                $videoCast->image_url = null;
                $videoCast->image_path = null;
                $videoCast->save();
                $deleteStatus = true;
            } else {
                $deleteStatus = false;
            }
            return $deleteStatus;
        } else {
            return false;
        }
    }

    public function videoStatusUpdate($id)
    {
        try {
            $updateStatus = $this->video->where('id', $id)->update(['is_active' => $this->request->status]);
            app('cache')->tags('videos')->flush();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
    /**
     * Repository function to store Meta Data.
     *
     * @param integer $id
     * The id of the cast image.
     * @return boolean True if the metadata is stored and false if not.
     */
    public function generateMetaData()
    {


        $checkVideoExist = VideoMetaData::where('video_id', $this->request->video_id)->first();
        if ($checkVideoExist) {

            $checkVideoExist->custom_url = $this->request->custom_url;
            $checkVideoExist->title = $this->request->title;
            $checkVideoExist->description = $this->request->description;
            $checkVideoExist->keyword = $this->request->keyword;
            $checkVideoExist->updator_id = \Auth::user()->id;
            $checkVideoExist->save();
        } else {
            $this->setRules(['custom_url' => 'unique:video_metadata']);
            $this->validate($this->request, $this->getRules());
            $checkVideoExist = new VideoMetaData();
            $checkVideoExist->video_id = $this->request->video_id;
            $checkVideoExist->custom_url = $this->request->custom_url;
            $checkVideoExist->title = $this->request->title;
            $checkVideoExist->description = $this->request->description;
            $checkVideoExist->keyword = $this->request->keyword;
            $checkVideoExist->creator_id = \Auth::user()->id;
            $checkVideoExist->save();
        }
    }
    /**
     * Repository function is to  get video Performance Statistics.
     *
     *     
     */
    public function getvideoPerformanceStatistics()
    {
        $video_id = $this->request->video_id;
        $dateType = $this->request->dataType;
        $this->video->performanceStatistics = $this->dashboardRepository->getPerformanceSubscribeAnalytics($video_id, $dateType);
        return $this->video;
    }
    /**
     * Repository function is to  get videoGeographic Statistics.
     *
     *     
     */
    public function getvideoGeographicStatistics()
    {
        $video_id = $this->request->video_id;
        $dateType = $this->request->dataType;
        $this->video->geographicStatistics = $this->dashboardRepository->getGeographicAnalytics($video_id, $dateType);
        return $this->video;
    }

    public function postTranscodeStatus($id)
    {
        if (!empty($id)) {
            $videoModel = $this->video->findorfail($id);
            $videoModel->transcode_status = 'Complete';
            $videoModel->save();
            return true;
        } else {
            return false;
        }
    }
    public function saveVideoIntoElastic($videoID)
    {
        $videoDetails = $this->bulkExportSearchData($videoID);
        $endpoint = 'add?_id=' . $videoID;
        $this->callElasticsearchService('PUT', $endpoint, $videoDetails[0]);
    }
    /**
     * Method to bulk the video data to be imported into the elasticsearch
     * 
     * @param int $videoID
     * @return Collection
     */
    public function bulkExportSearchData($videoID = null)
    {
        /** Place the AWS Bucket URL into the query */
        $bucketURL = env('AWS_BUCKET_URL');
        $exportBuilder = \DB::table('videos')
            ->select(\DB::raw('videos.id, videos.title, videos.slug, videos.description, categories.title as video_category_name, CONCAT("' . $bucketURL . '", videos.thumbnail_image) AS thumbnail_image, CONCAT("' . $bucketURL . '", videos.poster_image) AS poster_image, groups.name as genre_name, videos.is_active, videos.is_archived, videos.job_status, GROUP_CONCAT(tags.name) AS tags, videos.presenter, videos.published_on, videos.created_at, videos.release_year, videos.age_limit'))
            ->leftJoin('video_categories', 'videos.id', '=', 'video_categories.video_id')
            ->leftJoin('categories', 'video_categories.category_id', '=', 'categories.id')
            ->leftJoin('video_tag', 'videos.id', '=', 'video_tag.video_id')
            ->leftJoin('tags', 'video_tag.tag_id', '=', 'tags.id')
            ->leftJoin('collections_videos', 'videos.id', '=', 'collections_videos.video_id')
            ->leftJoin('groups', 'collections_videos.group_id', '=', 'groups.id');
        if (!empty($videoID)) {
            $exportBuilder = $exportBuilder->where('videos.id', '=', $videoID);
        }
        return $exportBuilder->groupBy('videos.id')->get()->toArray();
    }

    public function deleteVideoTrailer($id)
    {
        /**
         * Check if video id exists.
         */
        if (!empty($id)) {
            $video = $this->video->findorfail($id);
            $trailerData = $video->trailer_url;
            if (!empty($trailerData)) {
                $URL = $video->getImageBaseName($trailerData);
                /** call to method to delete image in S3 bucket */
                $trailerURL = config("contus.base.media.video_trailer.s3_location_trailer_source") . $URL;
                $this->uploadRepository->deleteFileFromS3Bucket($trailerURL);
                /** Process to delete image from local storage path */
                /**
                 * Empty the image_url and image_path field in the database.
                 */
                $video->trailer_url = '';
                $video->save();
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    // public function togglePublishNow($id)
    // {
    //     $video = $this->video->find($id);

    //     if ($video) {
    //         $video->is_active = $video->is_active == 1 ? 0 : 1;
    //         $video->save();
    //     }

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Video Published Successfully.'
    //     ]);
    // }
}
