<?php

/**
 * Ads Repository
 *
 * To manage the functionalities related to the Ads module from Ads Controller
 *
 * @name AdsRepository
 * @vendor Contus
 * @package Audio
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2018 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Audio\Repositories;

use Contus\Audio\Models\AudioAds;
use Contus\Audio\Repositories\AWSUploadRepository;
use Contus\Base\Helpers\StringLiterals;
use Contus\Base\Repositories\UploadRepository;
use Contus\Base\Repository as BaseRepository;

class AdsRepository extends BaseRepository
{
    /**
     * Class property to hold the key which hold the user object
     *
     * @var object
     */
    protected $_ads;
    /**
     * Class property to hold the key which hold the group name requested
     *
     * @var string
     */
    protected $requestedArtists = 'q';
    /**
     * Construct method
     *
     * @vendor Contus
     * AdAudio field is required
     * @package Audio
     * @param Contus\Audio\Models\Artist $artist
     */
    public function __construct(AudioAds $audioAds, UploadRepository $uploadRepository)
    {
        parent::__construct();
        $this->_ads = $audioAds;
        $this->uploadRepository = $uploadRepository;
        $this->awsRepository = new AWSUploadRepository();
        $this->setRules(['ad_name' => 'required','ad_audio' => 'required', 'image' => 'required', 'ad_url' => 'required']);
        $this->audioAdsfileBasePath = 'public' . DIRECTORY_SEPARATOR . config('contus.audio.audiomedia.audioAds.temporary_storage_path');
    }
    /**
     * Store a newly created artists.
     *
     * @param $id input
     * values
     *
     * @vendor Contus
     * @package audio
     * @return boolean
     */

    public function addOrUpdateAd($id = null)
    {
        if (!empty($id)) {
            $ad = $this->_ads->find($id);
            $this->setRules(['ad_name' => 'required','ad_audio' => 'required', 'image' => 'required', 'ad_url' => 'required']);
            $ad->updator_id = \Auth::user()->id;
        } else {
            if (empty(\Auth::user()->id)) {
                return "session_expire";
            } else {
                $ad = new AudioAds();
                $ad->creator_id = \Auth::user()->id;
            }
        }
        $this->_validate();
        $ad->fill($this->request->except('_token'));
        $imageArray = [];
        if ($this->request->image && $this->request->is_image_updated == 1) {
            $this->deleteAdImage($id);
            $fileName = $ad->getImageBaseName($this->request->image);
            $folderName = config("contus.base.image.ad_image.s3_location");
            $localStoragePath = public_path() . DIRECTORY_SEPARATOR . config("contus.base.image.ad_image.temporary_image_storage_path");
            $s3BucketImgFilename = $this->uploadTos3Bucket($fileName, $folderName, $localStoragePath);
            $s3BucketImgURL = $folderName . $s3BucketImgFilename;
            $ad->ad_image = $s3BucketImgURL;
        }

        if ($this->request->has('newAudioUUID') && $this->request->newAudioUUID != '') {
            $ad->audio_ad_job_status = 'Audio Uploaded';
            $ad->audio_ad_fine_uploader_uuid = $this->request->newAudioUUID;
            $newName = str_replace(' ', '', $this->request->newAudioName);
            $ad->audio_ad_fine_uploader_name = $newName;
            $ad->audio_ad_transcoding_percentage = 0;  
            $fileName = $this->request->newAudioUUID.DIRECTORY_SEPARATOR.$ad->getImageBaseName($newName);          
            $folderName = config("contus.audio.audiomedia.audioAds.s3_location_audio_ad_source");
            $localStoragePath = public_path() . DIRECTORY_SEPARATOR . config("contus.audio.audiomedia.audioAds.temporary_storage_path");
            $s3BucketAudioAdsFilename = $this->uploadTos3Bucket($fileName, $folderName, $localStoragePath);
            $s3BucketAudioAdsURL = $folderName.$this->request->newAudioUUID.DIRECTORY_SEPARATOR.$s3BucketAudioAdsFilename;
            $ad->audio_ad_audio_url = $s3BucketAudioAdsURL;

            $file = base_path($this->audioAdsfileBasePath . DIRECTORY_SEPARATOR . $this->request->newAudioUUID . DIRECTORY_SEPARATOR . $newName);
            $getID3 = new \getID3();
            $fileGetProperties = $getID3->analyze($file);
            $audio_duration	= formatTime($fileGetProperties['playtime_string']);
            $ad->ad_audio_duration	= $audio_duration;            
        }

        if ($ad->save()) {
            return true;
        }
    }
    /**
     * Repository function to delete thumbnail.
     *
     * @param integer $id
     * @return boolean True if the thumbnail is deleted and false if not.
     */

    public function deleteAdImage($id)
    {
        /**
         * Check if artist id exists.
         */
        if (!empty($id)) {
            $ad = $this->_ads->findorfail($id);
            $adImage = $ad->ad_image;
            if (!empty($adImage)) {
                $URL = $ad->getImageBaseName($ad->ad_image);
                /** call to method to delete image in S3 bucket */
                $imageURL = config("contus.base.image.ad_image.s3_location") . $URL;

                $this->uploadRepository->deleteFileFromS3Bucket($imageURL);
                /** Process to delete image from local storage path */
                $localFilePath = public_path() . DIRECTORY_SEPARATOR . config("contus.base.image.ad_image.temporary_image_storage_path");

                $this->uploadRepository->deleteImageFileInLocalPath($localFilePath, $URL);
                /**
                 * Empty the image_url and image_path field in the database.
                 */
                $ad->ad_image = '';
                $ad->save();
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Fetch users to display in admin block.
     *
     * @vendor Contus
     *
     * @package Audio
     * @return response
     */
    public function getArtists($status)
    {
        return $this->_artist->filter($status)->paginate(10);
    }
    /**
     * Fetch user to edit.
     *
     * @vendor Contus
     *
     * @package Audio
     * @return response
     */
    public function getArtist($id)
    {
        return $this->_artist->find($id);
    }
    /**
     * Prepare the grid
     * set the grid model and relation model to be loaded
     *
     * @vendor Contus
     *
     * @package Audio
     * @return Contus\Audio\Repositories\BaseRepository
     */
    public function prepareGrid()
    {
        $this->setGridModel($this->_ads);
        return $this;
    }

    public function getGridHeadings()
    {
        return ['heading' => [
            ['name' => trans('audio::audioAds.ad_name'), 'value' => 'ad_name', 'sort' => true],
            ['name' => trans('audio::audioAds.transcoding_status'), 'value' => 'transcoding_status', 'sort' => false],
            ['name' => trans('audio::audioAds.status'), 'value' => 'is_active', 'sort' => false],
            ['name' => trans('audio::audioAds.added_on'), 'value' => '', 'sort' => false],
            ['name' => trans('audio::audioAds.action'), 'value' => '', 'sort' => false],
        ]];
    }

    /**
     * Function to apply filter for search of Artists grid
     *
     * @param mixed $builderArtists
     * @return \Illuminate\Database\Eloquent\Builder $builderArtists The builder object of artists grid.
     */
    protected function searchFilter($builderAds)
    {
        $searchRecordAds = $this->request->has(StringLiterals::SEARCHRECORD) && is_array($this->request->input(StringLiterals::SEARCHRECORD)) ? $this->request->input(StringLiterals::SEARCHRECORD) : [];
        $ad_name = $is_active = null;
        extract($searchRecordAds);

        /**
         * Check if the name of the artist is present in the artist search.
         * If yes, then use it in filter.
         */
        if ($ad_name) {
            $builderAds = $builderAds->where('ad_name', 'like', '%' . $ad_name . '%');
        }

        /**
         * Check if the status of the artist is present in the artist search.
         * If yes, then use it in filter.
         */
        if (is_numeric($is_active)) {
            $builderAds = $builderAds->where(StringLiterals::ISACTIVE, $is_active);
        }

        return $builderAds;
    }

    /**
     * Repository function to get the artist related audio list
     *
     * @param integer $id
     * @return variable
     */
    public function getAudioArtists($id)
    {
        $this->artists = $this->_artist->find($id);
        if (is_null($this->artists)) {
            return $this->artists;
        }
        $audios = $this->artists->audio()->with('album')->where('is_archived', 0)->paginate(10)->toArray();
        return ['artist' => $this->artists, 'audios' => $audios];
    }

    /**
     * update grid records collection query
     *
     * @param mixed $builder
     * @return mixed
     */
    protected function updateGridQuery($builder)
    {
        return $builder->selectRaw('audio_ads.*,audio_ads.id as formatted_created_date');
    }

}
