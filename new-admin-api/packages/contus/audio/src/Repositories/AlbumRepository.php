<?php

/**
 * AlbumRepository
 *
 * To manage the audio album management such as create, edit and delete
 *
 * @name AlbumRepository
 * @version 1.0
 * @author Contus Team <developers@contus.in>
 * @copyright Copyright (C) 2018 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Audio\Repositories;

use Contus\Audio\Models\Albums;
use Contus\Audio\Models\Artist;
use Contus\Audio\Models\Audios;
use Contus\Audio\Models\FavouriteAlbum;
use Contus\Audio\Repositories\AudioRepository;
use Contus\Audio\Repositories\AWSUploadRepository;
use Contus\Base\Helpers\StringLiterals;
use Contus\Base\Repositories\UploadRepository;
use Contus\Base\Repository as BaseRepository;

class AlbumRepository extends BaseRepository
{
    /**
     * Class construct method initialization
     */
    public function __construct()
    {
        parent::__construct();
        $this->albums = new Albums();
        $this->audios = new Audios();
        $this->audioRepository = new AudioRepository();
        $this->favAlbums = new FavouriteAlbum();
        $this->uploadRepository = new UploadRepository();
        $this->awsRepository = new AWSUploadRepository();
        $this->setRules([
            'album_name' => 'required|unique:audio_albums,album_name',
            'album_artists' => 'required',
            'audio_language' => 'required',
        ]);
    }
    /**
     * Method to set the grid model and relation model to be loaded
     *
     * @vendor Contus
     * @return Contus\Collection\Repositories\BaseRepository
     */
    public function prepareGrid()
    {
        $this->setGridModel($this->albums)->setEagerLoadingModels(['artist', 'audios']);
        return $this;
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
                ['name' => trans('audio::album.album_name'), 'value' => 'album_name', 'sort' => true, 'class' => 'false'],
                ['name' => trans('audio::album.artist'), 'value' => 'album_artist_name', 'sort' => false, 'class' => 'false'],
                ['name' => trans('audio::album.no_of_audios'), 'value' => 'no_of_audios', 'sort' => false, 'class' => 'false'],
                ['name' => trans('audio::album.release_date'), 'value' => 'album_release_date', 'sort' => true, 'class' => 'false'],
                ['name' => trans('base::audio.status'), 'value' => 'is_active', 'sort' => false, 'class' => 'false'],
                ['name' => trans('base::audio.action'), 'value' => 'action', 'sort' => false, 'class' => 'false'],
            ],
        ];
    }

    public function addOrUpdateAlbum($id = null)
    {
        $is_active = '';
        if (!empty($id)) {
            $albums = $this->albums->find($id);
            $albums->updator_id = \Auth::user()->id;
            $this->setRule('album_name', 'required|unique:audio_albums,album_name,'.$id);
        } else {
            if (empty(\Auth::user()->id)) {
                return "session_expire";
            } else {
                $albums = new Albums();
                $albums->creator_id = \Auth::user()->id;
            }
        }
        $this->_validate();
        $is_active = ($this->request->is_active) ? 1 : 0;
        $albums->fill($this->request->except('_token'));
        $albums->album_artist_id = $this->request->album_artists;
        $albums->audio_language_category_id = $this->request->audio_language;
        $albums->genre_id = ($this->request->has('audio_genre')) ? $this->request->audio_genre : 0;
        $albums->album_release_date = (isset($this->request->album_release_date)) ? date("Y-m-d", strtotime($this->request->album_release_date)) : date("Y-m-d");
        $albums->is_active = $is_active;
        if ($this->request->thumbnail_image && $this->request->is_thumbnail_updated == 1) {
            $fileName = $albums->getImageBaseName($this->request->thumbnail_image);
            $folderName = config("contus.base.image.album_image.s3_location");
            $localStoragePath = public_path() . DIRECTORY_SEPARATOR . config("contus.base.image.album_image.temporary_image_storage_path");
            \Log::info($fileName);
            \Log::info($folderName);
            \Log::info($localStoragePath);
            $s3BucketImgFilename = $this->uploadTos3Bucket($fileName, $folderName, $localStoragePath);
            $s3BucketImgURL = $folderName . $s3BucketImgFilename;
            $albums->album_thumbnail = $s3BucketImgURL;
            if (!empty($id)) {
                $album = $this->albums->findorfail($id);
                $albumImage = $album->album_thumbnail;
                $baseName = strlen(env('AWS_BUCKET_URL'));
                $thumbnailImage = substr($albumImage, $baseName);
                if (!empty($thumbnailImage)) {
                    $updateAudioThumbnail = Audios::where('audio_thumbnail', $thumbnailImage)->update(['audio_thumbnail' => $s3BucketImgURL]);
                }
            }
            $this->deleteAlbumImage($id);
        }
        return ($albums->save()) ? [$albums->id, $albums->album_thumbnail] : false;
    }

    /**
     * Repository function to delete thumbnail.
     *
     * @param integer $id
     * @return boolean True if the thumbnail is deleted and false if not.
     */

    public function deleteAlbumImage($id)
    {
        /**
         * Check if album id exists.
         */
        if (!empty($id)) {
            $album = $this->albums->findorfail($id);
            $albumImage = $album->album_thumbnail;
            if (!empty($albumImage)) {

                $URL = $album->getImageBaseName($album->album_thumbnail);
                /** call to method to delete image in S3 bucket */
                $imageURL = config("contus.base.image.album_image.s3_location") . $URL;

                $this->uploadRepository->deleteFileFromS3Bucket($imageURL);
                /** Process to delete image from local storage path */
                $localFilePath = public_path() . DIRECTORY_SEPARATOR . config("contus.base.image.album_image.temporary_image_storage_path");
                $this->uploadRepository->deleteImageFileInLocalPath($localFilePath, $URL);
                /**
                 * Empty the image_url and image_path field in the database.
                 */
                $album->album_thumbnail = '';
                $album->save();
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Function to apply filter for search of album grid
     *
     * @param mixed $builderAlbum
     * @return \Illuminate\Database\Eloquent\Builder $builderAlbum The builder object of albums grid.
     */
    protected function searchFilter($builderAlbums)
    {
        $searchRecordAlbums = $this->request->has(StringLiterals::SEARCHRECORD) && is_array($this->request->input(StringLiterals::SEARCHRECORD)) ? $this->request->input(StringLiterals::SEARCHRECORD) : [];
        $album_name = $artist_name = $is_active = null;
        extract($searchRecordAlbums);
        /**
         * Check if the title of the album is present in the album search.
         * If yes, then use it in filter.
         */
        if ($album_name) {
            $builderAlbums = $builderAlbums->where('album_name', 'like', '%' . $album_name . '%');
        }
        /**
         * Check if the Artist id of the album is present in the album search.
         * If yes, then use it in filter.
         */
        if ($artist_name) {
            $builderAlbums = $builderAlbums->whereHas('artist', function ($query) use ($artist_name) {
                $query->where('audio_artists.artist_name', 'like', '%'. $artist_name . '%');
            });
        }
        /**
         * Check if the status of the album is present in the album search.
         * If yes, then use it in filter.
         */
        if (is_numeric($is_active)) {
            $builderAlbums = $builderAlbums->where(StringLiterals::ISACTIVE, $is_active);
        }
        return $builderAlbums;
    }

    public function getAlbum($id)
    {
        return $this->albums->with('audios')->where('id', $id)->first();
    }

    /**
     * Repository function to get the album related audio list
     *
     * @param integer $id
     * @return variable
     */
    public function getAudioAlbums($id)
    {
        $this->albums = $this->albums->find($id);
        if (is_null($this->albums)) {
            return $this->albums;
        }
        $audios = $this->albums->audios()->with('artist')->where('is_archived', 0)->paginate(10)->toArray();
        $favAlbumsCount = $this->favAlbums->where('album_id', (int) $id)->count();
        return ['album' => $this->albums, 'audios' => $audios, 'favAlbumsCount' => $favAlbumsCount];
    }

    /**
     * Method to update the audio data as bulk
     *
     * @vendor contus
     * @return boolean
     */
    public function audioBulkupdate()
    {
        $albumId = $this->request->albumId;
        $albumThumbnail = $this->request->albumThumbnail;
        $audioData = $this->request->audioPostData;
        foreach ($audioData as $data) {
            if (array_key_exists("id", $data)) {
                $is_active = ($data['is_active']) ?: 0;
                $audioId = $data['id'];
                $audioModel = $this->audios->findorfail($audioId);
                $audioModel->audio_title = $data['audio_title'];
                $audioModel->audio_description = (!empty($data['description'])) ? $data['description'] : null;
                $audioModel->album_id = (array_key_exists('audio_album', $data)) ? $data['audio_album'] : $albumId;
                if ($this->request->has('albumThumbnail') && ($this->request->albumThumbnail != '')) {
                    $baseName = strlen(env('AWS_BUCKET_URL'));
                    $thumbnailImage = substr($albumThumbnail, $baseName);
                    $audioModel->audio_thumbnail = $thumbnailImage;
                }
                if (array_key_exists('thumbnail_image', $data)) {
                    if ($data['thumbnail_image'] && $data['is_thumbnail_updated'] == 1) {
                        $fileName = $audioModel->getImageBaseName($data['thumbnail_image']);
                        $folderName = config("contus.base.image.audio_image.s3_location");
                        $localStoragePath = public_path() . DIRECTORY_SEPARATOR . config("contus.base.image.audio_image.temporary_image_storage_path");
                        $s3BucketImgFilename = $this->uploadTos3Bucket($fileName, $folderName, $localStoragePath);
                        $s3BucketImgURL = $folderName . $s3BucketImgFilename;
                        $audioModel->audio_thumbnail = $s3BucketImgURL;
                    }
                }
                $audioModel->audio_artist_id = $data['audio_artists'];
                $audioModel->is_active = $is_active;
                $audioModel->save();
            }
        }
        return true;
    }
}