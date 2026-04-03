<?php

namespace Contus\Tvshow\Api\Controllers\Admin;

use Contus\Base\ApiController;
use Contus\Base\Repositories\UploadRepository;
use Contus\Tvshow\Repositories\SeasonEpisodeRepository;

class SeasonEpisodeControllers extends ApiController
{
    protected $_episodeUpload;
    public function __construct(SeasonEpisodeRepository $seasonEpisodeRepository, UploadRepository $uploadRepository)
    {
        parent::__construct();
        $this->repository = $seasonEpisodeRepository;
        $this->_episodeUpload = $uploadRepository;
        $this->repository->setRequestType(static::REQUEST_TYPE);
    }


    public function postThumbnail()
    {
        $moduleName = 'tvshow-season-episode-image';
        $tempImageInfo = $this->_episodeUpload
            ->setModelIdentifier(UploadRepository::MODEL_IDENTIFIER_THUMBNAIL)->tempPrepare()
            ->tempUpload($moduleName, $this->request->size);

        return empty($tempImageInfo) ? $this->getErrorJsonResponse([], trans('video::videos.messsage.resolutionInvalid')) :
            $this->getSuccessJsonResponse(['info' => array_shift($tempImageInfo)]);
    }

    public function postPosters()
    {
        $moduleName = 'tvshow-season-episode-image';
        $tempImageInfo = $this->_episodeUpload
            ->setModelIdentifier(UploadRepository::MODEL_IDENTIFIER_POSTER)->tempPrepare()
            ->tempUpload($moduleName, $this->request->size);

        return empty($tempImageInfo) ? $this->getErrorJsonResponse([], trans(StringLiterals::UNABLE_TO_UPLOAD)) :
            $this->getSuccessJsonResponse(['info' => array_shift($tempImageInfo)]);
    }

    public function CreateEpisode()
    {
        $season = $this->repository->CreateEpisode();
        if ($season == 'success') {
            return $this->getSuccessJsonResponse(['message' => 'TV Show Season Data Create.']);
        } else {
            return $this->getErrorJsonResponse([], $season);
        }
    }

    public function getEpisodeToEdit($id)
    {
        $showId = $this->repository->getEpisodeToEdit($id);
        return (is_null($showId)) ?
            $this->getErrorJsonResponse([], null, 404) :
            $this->getSuccessJsonResponse(['response' => $showId]);
    }

    public function postEdit($id)
    {
        $editId = $this->repository->postEdit($id);
        if ($editId == 'success') {
            return $this->getSuccessJsonResponse(['message' => 'Episode Data Updated Successfully.']);
        } else {
            return $this->getErrorJsonResponse([], $editId);
        }
    }

    public function postDelete($id)
    {
        $remvId = $this->repository->postDelete($id);
        if ($remvId == 'success') {
            return $this->getSuccessJsonResponse(['message' => 'Episode Data Deleted Successfully.']);
        } else {
            return $this->getErrorJsonResponse([], $remvId);
        }
    }

    public function postToggle($id)
    {
        $isUpdated = true;
        if ($this->repository->postToggle($id)) {
            return ($isUpdated) ?
                $this->getSuccessJsonResponse(['message' => 'Data Update Successfully.']) :
                $this->getErrorJsonResponse([], 'Data Not Update.');
        }
    }

    public function fetchRecords()
    {
        $response = ['data' => $this->repository->fetchRecords()];
        if ($this->request->input('intialRequest') == 1) {
            $response['heading'] = $this->repository->getGridHeadings();
            $response['moreInfo'] = $this->repository->getGridAdditionalInformation();
            $response['recordsCount'] = $this->repository->getCount();
        }
        return $this->getSuccessJsonResponse($response);
    }

    public function fetchEpisodeRecords()
    {
        $response = ['data' => $this->repository->fetchEpisodeRecords()];
        if ($this->request->input('intialRequest') == 1) {
            $response['heading'] = $this->repository->getGridHeadings();
            $response['moreInfo'] = $this->repository->getGridAdditionalInformation();
            $response['recordsCount'] = $this->repository->getCount();
        }
        return $this->getSuccessJsonResponse($response);
    }
}