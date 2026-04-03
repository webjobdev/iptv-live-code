<?php

namespace Contus\Vod\Api\Controllers\Admin;
use BadMethodCallException;
use Contus\Base\ApiController;
use Contus\Base\Helpers\StringLiterals;
use Contus\Base\Repositories\UploadRepository;
use Contus\Vod\Repositories\VodIndexRepository;
use Contus\Vod\Repositories\VodUploadRepository;
use Google\Service\FirebaseRules\FunctionMock;
use Contus\Base\Contracts\GridableRepository;


class VodIndexController extends ApiController
{

    protected $vodrepository;
    public $_vodUpload;

    public function __construct(VodIndexRepository $vodIndexRepository, UploadRepository $uploadRepository)
    {
        parent::__construct();
        $this->repository = $vodIndexRepository;
        $this->_vodUpload = $uploadRepository;
        $this->repository->setRequestType(static::REQUEST_TYPE);
    }

    public function getInfo()
    {
        return $this->getSuccessJsonResponse(['success']);
    }

    public function postThumbnail()
    {
        $moduleName = 'vod-image';
        $tempImageInfo = $this->_vodUpload
            ->setModelIdentifier(UploadRepository::MODEL_IDENTIFIER_THUMBNAIL)->tempPrepare()
            ->tempUpload($moduleName, $this->request->size);
        return empty($tempImageInfo) ? $this->getErrorJsonResponse([], trans('video::videos.messsage.resolutionInvalid')) :
            $this->getSuccessJsonResponse(['info' => array_shift($tempImageInfo)]);
    }

    public function postPosters()
    {
        $moduleName = 'vod-image';
        $tempImageInfo = $this->_vodUpload->setModelIdentifier(UploadRepository::MODEL_IDENTIFIER_POSTER)->tempPrepare()
            ->tempUpload($moduleName, $this->request->size);

        return empty($tempImageInfo) ? $this->getErrorJsonResponse([], trans(StringLiterals::UNABLE_TO_UPLOAD)) : $this->getSuccessJsonResponse(['info' => array_shift($tempImageInfo)]);
    }

    public function CreateVod()
    {
        $vod = $this->repository->VideoOnDemand();
        if ($vod == 'success') {
            return $this->getSuccessJsonResponse(['message' => 'Video On Demad Data Createeeeeeeeee.']);
        } else {
            return $this->getErrorJsonResponse([], $vod);
        }
    }

    public function postEdit($id)
    {
        $isUpdated = false;

        if ($this->repository->VodUpdate($id)) {
            $isUpdated = true;
            return ($isUpdated) ?
                $this->getSuccessJsonResponse(['message' => 'Vod Data Update Successfully.']) :
                $this->getErrorJsonResponse([], 'Vod Data Not Update.');
        }
    }

    public function getVodToEdit($id)
    {
        $vodId = $this->repository->getVod($id);
        return (is_null($vodId)) ?
            $this->getErrorJsonResponse([], null, 404) :
            $this->getSuccessJsonResponse(['response' => $vodId]);
    }

    public function postToggle($id)
    {
        $isUpdated = true;
        if ($this->repository->postToggle($id)) {
            return ($isUpdated) ?
                $this->getSuccessJsonResponse(['message' => 'Channel Data Update Successfully.']) :
                $this->getErrorJsonResponse([], 'Channel Data Not Update.');
        }
    }

    public function postDeleteAction()
    {
        If (property_exists($this, StringLiterals::REPOSITORY) && $this->repository instanceof GridableRepository) {
            if ($this->repository->prepareGrid()->action()) {
                // $this->request->session()->flash('success', trans('base::general.success_delete' ));
                return $this->getSuccessJsonResponse([], trans('base::general.success_delete'));
            } else {
                // $this->request->session()->flash('error', trans( 'base::general.invalid_request' ));
                return $this->getErrorJsonResponse([], trans('base::general.invalid_request'), 403);
            }
            //return $this->repository->prepareGrid ()->action () ? $this->getSuccessJsonResponse ( [ ], trans ( 'base::general.success_delete' ) ) : $this->getErrorJsonResponse ( [ ], trans ( 'base::general.invalid_request' ), 403 );
        }

        throw new BadMethodCallException("Method [postAction] does not exist.");
    }
}
