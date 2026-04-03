<?php

namespace Contus\Organizations\Api\Controller;

use Contus\Base\ApiController;
use Contus\Base\Repositories\UploadRepository;
use Contus\Organizations\Repositories\TvShowContetntSetRepository;
use Illuminate\Support\Facades\Auth;
use Contus\Organizations\Repositories\ContetntSetRepository;

class TvShowContentSetController extends ApiController
{
    protected $_tvsContentUpload;

    public function __construct(TvShowContetntSetRepository $tvshowContetntSetRepository, UploadRepository $uploadRepository)
    {
        parent::__construct();
        $this->repository = $tvshowContetntSetRepository;
        $this->_tvsContentUpload = $uploadRepository;
        $this->repository->setRequestType(static::REQUEST_TYPE);
    }

    public function getInfo()
    {
        $user = Auth::user();
        return $this->getSuccessJsonResponse(['success']);
    }

    public function postAdd()
    {
        $channelData = $this->repository->postAdd();
        if ($channelData == 'success') {
            return $this->getSuccessJsonResponse(['message' => 'Channel Content Sets created successfully']);
        } else {
            return $this->getErrorJsonResponse([], $channelData);
        }
    }

    public function postPosters()
    {
        $moduleName = 'tvshow-season-episode-image';
        $tempImageInfo = $this->_tvsContentUpload
            ->setModelIdentifier(UploadRepository::MODEL_IDENTIFIER_POSTER)->tempPrepare()
            ->tempUpload($moduleName, $this->request->size);

        return empty($tempImageInfo) ? $this->getErrorJsonResponse([], trans(StringLiterals::UNABLE_TO_UPLOAD)) :
            $this->getSuccessJsonResponse(['info' => array_shift($tempImageInfo)]);
    }

    public function postEdit($id)
    {
        $channelId = $this->repository->postEdit($id);
        if ($channelId == 'success') {
            return $this->getSuccessJsonResponse(['message' => 'Channel Content Sets Updated successfully']);
        } else {
            return $this->getErrorJsonResponse([], $channelId);
        }
    }

    public function fetchRecords()
    {
        $api = $this->repository->fetchRecords();

        return response()->json([
            "error" => false,
            "statusCode" => 200,
            "status" => "success",
            "message" => null,
            'data' => $api
        ], 200);
    }

}