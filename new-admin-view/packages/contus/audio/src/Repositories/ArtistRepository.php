<?php

/**
 * Artist Repository
 *
 * To manage the functionalities related to the Artists module from Artists Controller
 *
 * @name ArtistRepository
 * @vendor Contus
 * @package Artists
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Audio\Repositories;

use Contus\Audio\Models\Artist;
use Contus\Audio\Repositories\AWSUploadRepository;
use Contus\Base\Helpers\StringLiterals;
use Contus\Base\Repositories\UploadRepository;
use Contus\Base\Repository as BaseRepository;

class ArtistRepository extends BaseRepository
{

    /**
     * Class property to hold the key which hold the user object
     *
     * @var object
     */
    protected $_artist;
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
     *
     * @package Audio
     * @param Contus\Audio\Models\Artist $artist
     */
    public function __construct(Artist $artist, UploadRepository $uploadRepository)
    {
        parent::__construct();
        $this->_artist = $artist;
        $this->uploadRepository = $uploadRepository;
        $this->awsRepository = new AWSUploadRepository();
        $this->setRules(['artist_name' => 'required|unique:audio_artists,artist_name']);
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

    public function addOrUpdateArtist($id = null) {
        if (!empty($id)) {
            $artist = $this->_artist->find($id);
            $this->setRule('artist_name', 'required|unique:audio_artists,artist_name,'.$id);
        } else {
            if (empty($this->authUser->id)) {
                return "session_expire";
            } else {
                $artist = new Artist();
            }
        }
        $this->_validate();
        $artist->fill($this->request->except('_token'));
        if ($this->request->image && $this->request->is_image_updated == 1) {
            $this->deleteArtistImage($id);
            $fileName = $artist->getImageBaseName($this->request->image);
            $folderName = config("contus.base.image.artist_image.s3_location");
            $localStoragePath = public_path() . DIRECTORY_SEPARATOR . config("contus.base.image.artist_image.temporary_image_storage_path");
            $s3BucketImgFilename = $this->uploadTos3Bucket($fileName, $folderName, $localStoragePath);
            $s3BucketImgURL = $folderName . $s3BucketImgFilename;
            $artist->artist_thumbnail = $s3BucketImgURL;
        }
        if ($artist->save()) {
            return true;
        }
    }
    /**
     * Repository function to delete thumbnail.
     *
     * @param integer $id
     * @return boolean True if the thumbnail is deleted and false if not.
     */

    public function deleteArtistImage($id)
    {
        /**
         * Check if artist id exists.
         */
        if (!empty($id)) {
            $artist = $this->_artist->findorfail($id);
            $artistImage = $artist->artist_thumbnail;
            if (!empty($artistImage)) {
                $URL = $artist->getImageBaseName($artist->artist_thumbnail);
                /** call to method to delete image in S3 bucket */
                $imageURL = config("contus.base.image.artist_image.s3_location") . $URL;

                $this->uploadRepository->deleteFileFromS3Bucket($imageURL);
                /** Process to delete image from local storage path */
                $localFilePath = public_path() . DIRECTORY_SEPARATOR . config("contus.base.image.artist_image.temporary_image_storage_path");

                $this->uploadRepository->deleteImageFileInLocalPath($localFilePath, $URL);
                /**
                 * Empty the image_url and image_path field in the database.
                 */
                $artist->artist_thumbnail = '';
                $artist->save();
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
        $this->setGridModel($this->_artist)->setEagerLoadingModels(['audio', 'album']);
        return $this;
    }

    public function getGridHeadings()
    {
        return ['heading' => [
            ['name' => trans('audio::artists.artist_name'), 'value' => 'artist_name', 'sort' => true],
            ['name' => trans('audio::general.no_of_audio'), 'value' => 'is_active', 'sort' => false],
            ['name' => trans('audio::artists.status'), 'value' => 'is_active', 'sort' => false],
            ['name' => trans('audio::artists.added_on'), 'value' => '', 'sort' => false],
            ['name' => trans('audio::artists.action'), 'value' => '', 'sort' => false],
        ]];
    }

    /**
     * Function to apply filter for search of Artists grid
     *
     * @param mixed $builderArtists
     * @return \Illuminate\Database\Eloquent\Builder $builderArtists The builder object of artists grid.
     */
    protected function searchFilter($builderArtists)
    {

        $searchRecordArtists = $this->request->has(StringLiterals::SEARCHRECORD) && is_array($this->request->input(StringLiterals::SEARCHRECORD)) ? $this->request->input(StringLiterals::SEARCHRECORD) : [];
        $artist_name = $is_active = null;
        extract($searchRecordArtists);

        /**
         * Check if the name of the artist is present in the artist search.
         * If yes, then use it in filter.
         */
        if ($artist_name) {
            $builderArtists = $builderArtists->where('artist_name', 'like', '%' . $artist_name . '%');
        }

        /**
         * Check if the status of the artist is present in the artist search.
         * If yes, then use it in filter.
         */
        if (is_numeric($is_active)) {
            $builderArtists = $builderArtists->where(StringLiterals::ISACTIVE, $is_active);
        }

        return $builderArtists;
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
        return $builder->selectRaw('audio_artists.*,audio_artists.id as formatted_created_date');
    }

}
