<?php

namespace Contus\Tvshow\Api\Controllers\Admin;

use Contus\Base\ApiController;
use Contus\Base\Helpers\StringLiterals;
use Contus\Base\Repositories\UploadRepository;
use Contus\Tvshow\Repositories\TvShowIndexRepository;

class TvShowIndexControllers extends ApiController
{

    public $_TvShowUpload;

    public function __construct(TvShowIndexRepository $tvShowIndexRepository, UploadRepository $uploadRepository)
    {
        parent::__construct();
        $this->repository = $tvShowIndexRepository;
        $this->_TvShowUpload = $uploadRepository;
        $this->repository->setRequestType(static::REQUEST_TYPE);
    }

    public function getInfo()
    {
        return $this->getSuccessJsonResponse(['success']);
    }

    public function postThumbnail()
    {
        $moduleName = 'tvshow-image';
        $tempImageInfo = $this->_TvShowUpload
            ->setModelIdentifier(UploadRepository::MODEL_IDENTIFIER_THUMBNAIL)->tempPrepare()
            ->tempUpload($moduleName, $this->request->size);

        return empty($tempImageInfo) ? $this->getErrorJsonResponse([], trans('video::videos.messsage.resolutionInvalid')) :
            $this->getSuccessJsonResponse(['info' => array_shift($tempImageInfo)]);
    }

    public function postPosters()
    {
        $moduleName = 'tvshow-image';
        $tempImageInfo = $this->_TvShowUpload
            ->setModelIdentifier(UploadRepository::MODEL_IDENTIFIER_POSTER)->tempPrepare()
            ->tempUpload($moduleName, $this->request->size);

        return empty($tempImageInfo) ? $this->getErrorJsonResponse([], trans(StringLiterals::UNABLE_TO_UPLOAD)) :
            $this->getSuccessJsonResponse(['info' => array_shift($tempImageInfo)]);
    }

    public function CreateTvShow()
    {
        $tvShow = $this->repository->CreateTvShow();
        if ($tvShow == 'success') {
            return $this->getSuccessJsonResponse(['message' => 'TV Show Data Create.']);
        } else {
            return $this->getErrorJsonResponse([], $tvShow);
        }
    }

    public function postEdit($id)
    {
        $isUpdated = false;

        if ($this->repository->TvShowUpdate($id)) {
            $isUpdated = true;
            return ($isUpdated) ?
                $this->getSuccessJsonResponse(['message' => 'Vod Data Update Successfully.']) :
                $this->getErrorJsonResponse([], 'Vod Data Not Update.');
        }
    }

    public function getTvShowToEdit($id)
    {
        $showId = $this->repository->getTvShow($id);
        return (is_null($showId)) ?
            $this->getErrorJsonResponse([], null, 404) :
            $this->getSuccessJsonResponse(['response' => $showId]);
    }

    public function fetchdata()
    {
        $api = $this->repository->fetchdata($this->request);
        return response()->json([
            "error" => false,
            "statusCode" => 200,
            "status" => "success",
            "message" => null,
            'data' => $api
        ], 200);
    }

    public function togglePublishNow($id)
    {
        $isUpdated = false;

        if ($this->repository->togglePublishNow($id)) {
            $isUpdated = true;
            return ($isUpdated) ?
                $this->getSuccessJsonResponse(['message' => 'TV Show Data Update Successfully.']) :
                $this->getErrorJsonResponse([], 'TV Show Data Not Update.');
        }
    }

}