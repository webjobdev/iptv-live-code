<?php

/**
 * AudioRepository
 *
 * To manage the audio management such as create, edit and delete
 *
 * @name AudioRepository
 * @version 1.0
 * @author Contus Team <developers@contus.in>
 * @copyright Copyright (C) 2018 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Audio\Repositories;

use Carbon\Carbon;
use Contus\Audio\Models\Albums;
use Contus\Audio\Models\Audios;
use Contus\Audio\Repositories\AWSUploadRepository;
use Contus\Audio\Models\FavouriteAudio;
use Contus\Base\Helpers\StringLiterals;
use Contus\Base\Repositories\UploadRepository;
use Contus\Base\Repository as BaseRepository;

class AudioRepository extends BaseRepository
{
    /**
     * Class construct method initialization
     */
    public function __construct()
    {
        parent::__construct();
        $this->audios = new Audios();
        $this->favAudios = new FavouriteAudio();
        $this->uploadRepository = new UploadRepository();
        $this->awsRepository = new AWSUploadRepository();
        $this->setRules([
            'audio_title' => 'required',
            'audio_artists' => 'required',
            'audio_album' => 'required',
        ]);

    }
    /**
     * Method to save new audios
     *
     * @vendor Contus
     * @return integer
     */
    public function addAudio()
    {
        $audio_details = $audioTitle = '';
        $audio_details = $this->request->audio_details;
        $audioArr = explode('.', $audio_details['name']);
        $extension = strtolower(array_pop($audioArr));
        $audioTitle = implode('.', $audioArr);

        $audioModel = $this->audios;
        $audioModel->audio_title = $audioTitle;
        $audioModel->job_status = 'Audio Uploaded';
        $audioModel->fine_uploader_uuid = $audio_details['uuid'];
        $audioModel->fine_uploader_name = str_replace(' ', '', $audio_details['name']);
        $audioModel->is_active = 0;
        $audioModel->album_id = ($this->request->has('album_id')) ? $this->request->album_id : 0;
        $audioModel->creator_id = $this->authUser->id;
        $audioModel->updator_id = $this->authUser->id;
        return ($audioModel->save()) ? $audioModel : null;
    }

    /**
     * Store a newly update audios.
     *
     * @param $id input
     * values
     *
     * @vendor Contus
     * @package audio
     * @return boolean
     */
    public function addOrUpdateAudio($id = null){
        $is_active = '';
        if (!empty($id)) {
            $audios = $this->audios->find($id);
            $this->setRules([
                'audio_title' => 'required',
                'audio_artist' => 'required',
                'audio_album' => 'required',
            ]);
            $audios->updator_id = $this->authUser->id;

        } else {
            if (empty($this->authUser->id)) {
                return "session_expire";
            } else {
                $audios = new Audios();
                $audios->creator_id = $this->authUser->id;
            }
        }
        $this->_validate();
        $is_active = ($this->request->is_active) ? 1 : 0;
        $audios->fill($this->request->except('_token'));
        $audios->album_id = $this->request->audio_album;
        $audios->audio_artist_id = $this->request->audio_artist;
        $audios->is_active = $is_active;

        if ($this->request->thumbnail_image && $this->request->is_thumbnail_updated == 1) {
            $this->deleteAudioImage($id);
            $fileName = $audios->getImageBaseName($this->request->thumbnail_image);
            $folderName = config("contus.base.image.audio_image.s3_location");
            $localStoragePath = public_path() . DIRECTORY_SEPARATOR . config("contus.base.image.audio_image.temporary_image_storage_path");
            $s3BucketImgFilename = $this->uploadTos3Bucket($fileName, $folderName, $localStoragePath);
            $s3BucketImgURL = $folderName . $s3BucketImgFilename;
            $audios->audio_thumbnail = $s3BucketImgURL;
        }
        if ($this->request->has('newAudioUUID') && $this->request->newAudioUUID != '') {
            $audios->job_status = 'Audio Uploaded';
            $audios->fine_uploader_uuid = $this->request->newAudioUUID;
            $audios->fine_uploader_name = str_replace(' ', '', $this->request->newAudioName);
            $audios->transcoding_percentage = 0;
        }

        if ($audios->save()) {
            return true;
        }
    }

    /**
     * Repository function to delete thumbnail.
     *
     * @param integer $id
     * @return boolean True if the thumbnail is deleted and false if not.
     */

    public function deleteAudioImage($id)
    {
        /**
         * Check if album id exists.
         */
        if (!empty($id)) {
            $audio = $this->audios->findorfail($id);
            $audioImage = $audio->audio_thumbnail;

            if (!empty($audioImage)) {
                $URL = $audio->getImageBaseName($audio->audio_thumbnail);
                /** call to method to delete image in S3 bucket */
                $imageURL = config("contus.base.image.audio_image.s3_location") . $URL;

                $this->uploadRepository->deleteFileFromS3Bucket($imageURL);
                /** Process to delete image from local storage path */
                $localFilePath = public_path() . DIRECTORY_SEPARATOR . config("contus.base.image.audio_image.temporary_image_storage_path");
                $this->uploadRepository->deleteImageFileInLocalPath($localFilePath, $URL);
                /**
                 * Empty the image_url and image_path field in the database.
                 */
                $audio->audio_thumbnail = '';
                $audio->save();
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    /**
     * Method to set the grid model and relation model to be loaded
     *
     * @vendor Contus
     * @return Contus\Collection\Repositories\BaseRepository
     */
    public function prepareGrid()
    {
        $this->setGridModel($this->audios)->setEagerLoadingModels(['album', 'artist']);
        return $this;
    }

    /**
     * Get all Audio Album details
     *
     * @vendor Contus
     * @return array
     */
    public function getAllAlbum()
    {
        return Albums::select('id', 'album_name')->where('is_active', 1)->get()->toArray();
    }
    /**
     * Get headings for grid
     *
     * @vendor Contus
     * @return array
     */
    public function getGridHeadings()
    {
        return [
            'heading' => [
                ['name' => trans('audio::audio.audio_name'), 'value' => 'audio_title', 'sort' => true, 'class' => 'false'],
                ['name' => trans('base::audio.artist'), 'value' => 'artist_name', 'sort' => false, 'class' => 'false'],
                ['name' => trans('audio::audio.album'), 'value' => 'album_name', 'sort' => false, 'class' => 'false'],
                ['name' => trans('audio::audio.transcoding_status'), 'value' => 'transcoding_status', 'sort' => false, 'class' => 'false'],
                ['name' => trans('audio::audio.added_on'), 'value' => 'created_at', 'sort' => true, 'class' => 'false'],
                ['name' => trans('base::audio.status'), 'value' => 'is_active', 'sort' => false, 'class' => 'false'],
                ['name' => trans('base::audio.action'), 'value' => 'action', 'sort' => false, 'class' => 'false'],
            ],
        ];
    }

    /**
     * Function to apply filter for search of audio grid
     *
     * @param mixed $builderAlbum
     * @return \Illuminate\Database\Eloquent\Builder $builderAlbum The builder object of albums grid.
     */
    protected function searchFilter($builderAudios){
        $searchRecordAudios = $this->request->has(StringLiterals::SEARCHRECORD) && is_array($this->request->input(StringLiterals::SEARCHRECORD)) ? $this->request->input(StringLiterals::SEARCHRECORD) : [];
        $audio_title = $album_name = $artist_name = $is_active = null;
        extract($searchRecordAudios);
        $builderAudios = ($audio_title) ? $builderAudios->where('audio_title', 'like', '%' . $audio_title . '%'):$builderAudios;
        if ($artist_name) {
            $builderAudios = $builderAudios->whereHas('artist', function ($query) use ($artist_name) {
                $query->where('audio_artists.artist_name', 'like', '%' . $artist_name . '%');
            });
        }

        if ($album_name) {
            $builderAudios = $builderAudios->whereHas('album', function ($query) use ($album_name) {
                $query->where('audio_albums.album_name', 'like', '%' . $album_name . '%');
            });
        }

        return (is_numeric($is_active)) ? $builderAudios->where('is_active', $is_active):$builderAudios;
    }

    /**
     * Function to archive audios in the database.
     * This function works like a soft delete and the audio files in AWS S3 are not deleted.
     *
     * @param integer|array $ids
     * The ids of the audios which are to be deleted.
     * @return boolean True if the audios are archived successfully and false if not.
     */
    public function audioDelete($ids)
    {
        /**
         * Delete the audio by the given id
         */
        $ids = is_array($ids) ? $ids : [$ids];
        $status = false;
        if (!empty($ids)) {
            $this->audios->whereIn('id', $ids)->update([StringLiterals::IS_ARCHIVED => 1, 'archived_on' => Carbon::now()]);
            $status = true;
        }
        return $status;
    }

    /**
     * Function to activate the Audios
     *
     * @param integer|array $ids
     * The ids of the Audios which are to be activated.
     * @return boolean True if the Audios are archived successfully and false if not.
     */
    public function ActivateOrDeactivate($ids, $isStatus)
    {
        /**
         * Delete the audio by the given id
         */
        $ids = is_array($ids) ? $ids : [$ids];
        /**
         * Check if the status is activate.
         * If yes, set is_active field to 1.
         * If no, then set is_active field to 0.
         */
        if ($isStatus == 'activate') {
            $status = empty($ids) ? StringLiterals::LITERALFALSE : $this->audios->whereIn('id', $ids)->update([StringLiterals::ISACTIVE => 1]);
            return $status;
        } else if ($isStatus == 'deactivate') {
            $status = empty($ids) ? StringLiterals::LITERALFALSE : $this->audios->whereIn('id', $ids)->update([StringLiterals::ISACTIVE => 0]);
            return $status;
        }
    }

    /**
     * Function to fetch all the details of a audio from the database.
     *
     * @param integer $id
     * The id of the audio whose data are to be fetched.
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|NULL The information of the video.
     */
    public function getCompleteAudioDetails($id){
        $result = array();
        $this->audios = $this->audios->selectRaw('audios.*, audios.id as formatted_created_date, audios.id as formatted_updated_date')->groupBy('audios.id')->with(['artist', 'album', 'user'])->where('id', $id)->where(StringLiterals::IS_ARCHIVED, 0)->first();
        $result['audios_data'] = $this->audios;
        $result['favourites_count'] = $this->favAudios->where('audio_id', (int) $id)->count();
        return $result;
    }

    /**
     * Fetch audio to edit.
     *
     * @vendor Contus
     *
     * @package audio
     * @return response
     */
    public function getAudio($id){
        return $this->audios->where('id', $id)->where(StringLiterals::IS_ARCHIVED, 0)->first();
    }

    /**
     * update grid records collection query
     *
     * @param mixed $builder
     * @return mixed
     */
    protected function updateGridQuery($builder){
        return $builder->selectRaw('audios.*,audios.id as formatted_created_date')->where(StringLiterals::IS_ARCHIVED, 0);
    }
}
