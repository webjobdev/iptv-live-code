<?php

namespace Contus\Tvshow\Api\Controllers\Admin;

use Contus\Base\ApiController;
use Contus\Base\Helpers\StringLiterals;
use Contus\Base\Repositories\UploadRepository;
use Contus\Tvshow\Repositories\TvshowSeasonRepository;

class TvshowSeasonControllers extends ApiController
{

    protected $_tvShowSeason;

    public function __construct(TvshowSeasonRepository $tvshowSeasonRepository, UploadRepository $uploadRepository)
    {
        parent::__construct();
        $this->repository = $tvshowSeasonRepository;
        $this->_tvShowSeason = $uploadRepository;
        $this->repository->setRequestType(static::REQUEST_TYPE);
    }

    public function postThumbnail()
    {
        $moduleName = 'tvshow-season-image';
        $tempImageInfo = $this->_tvShowSeason
            ->setModelIdentifier(UploadRepository::MODEL_IDENTIFIER_THUMBNAIL)->tempPrepare()
            ->tempUpload($moduleName, $this->request->size);

        return empty($tempImageInfo) ? $this->getErrorJsonResponse([], trans('video::videos.messsage.resolutionInvalid')) :
            $this->getSuccessJsonResponse(['info' => array_shift($tempImageInfo)]);
    }

    public function postPosters()
    {
        $moduleName = 'tvshow-season-image';
        $tempImageInfo = $this->_tvShowSeason
            ->setModelIdentifier(UploadRepository::MODEL_IDENTIFIER_POSTER)->tempPrepare()
            ->tempUpload($moduleName, $this->request->size);

        return empty($tempImageInfo) ? $this->getErrorJsonResponse([], trans(StringLiterals::UNABLE_TO_UPLOAD)) :
            $this->getSuccessJsonResponse(['info' => array_shift($tempImageInfo)]);
    }

    public function CreateSeason()
    {
        $season = $this->repository->CreateSeason();
        if ($season == 'success') {
            return $this->getSuccessJsonResponse(['message' => 'TV Show Season Data Create.']);
        } else {
            return $this->getErrorJsonResponse([], $season);
        }
    }

    public function getTvShowToEdit($id)
    {
        $showId = $this->repository->getTvShowSeason($id);
        return (is_null($showId)) ?
            $this->getErrorJsonResponse([], null, 404) :
            $this->getSuccessJsonResponse(['response' => $showId]);
    }

    public function postEdit($id)
    {
        $editId = $this->repository->postEdit($id);
        if ($editId == 'success') {
            return $this->getSuccessJsonResponse(['message' => 'Season Data Updated Successfully.']);
        } else {
            return $this->getErrorJsonResponse([], $editId);
        }
    }

    public function postRemove($id)
    {
        $remvId = $this->repository->postRemove($id);
        if ($remvId == 'success') {
            return $this->getSuccessJsonResponse(['message' => 'Season Data Deleted Successfully.']);
        } else {
            return $this->getErrorJsonResponse([], $remvId);
        }
    }

    public function fetchRecords()
    {
        // if (property_exists($this, StringLiterals::REPOSITORY) && $this->repository instanceof GridableRepository) {
        $response = ['data' => $this->repository->fetchRecords()];
        // dd($response);
        if ($this->request->input('intialRequest') == 1) {
            $response['heading'] = $this->repository->getGridHeadings();
            $response['moreInfo'] = $this->repository->getGridAdditionalInformation();
            $response['recordsCount'] = $this->repository->getCount();
        }
        // dd($response);
        return $this->getSuccessJsonResponse($response);
        // }

        // throw new BadMethodCallException("Method [postRecords] does not exist.");
    }

    public function getRecords()
    {
        $response = ['data' => $this->repository->getRecords()];
        if ($this->request->input('intialRequest') == 1) {
            $response['heading'] = $this->repository->getGridHeadings();
            $response['moreInfo'] = $this->repository->getGridAdditionalInformation();
            $response['recordsCount'] = $this->repository->getCount();
        }
        return $this->getSuccessJsonResponse($response);
    }

}