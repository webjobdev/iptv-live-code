<?php

namespace Contus\Organizations\Api\Controller\AppCustomization;

use Contus\Base\ApiController;
use Contus\Organizations\Repositories\AppCustomization\AppCustomiztionFeaturedRowRepository;
use Google\Service\AdMob\App;
use Google\Service\ServiceManagement\Api;

class AppCustomiztionFeaturedRow extends ApiController
{
    protected $appCustomizationRepository;
    public function __construct(AppCustomiztionFeaturedRowRepository $app_customiztion_featured_row_repository)
    {
        parent::__construct();
        $this->repository = $app_customiztion_featured_row_repository;
        // $this->appCustomizationRepository = $appCustomizationRepository;
        $this->repository->setRequestType(static::REQUEST_TYPE);
    }

    public function postEdit($id)
    {
        $featuredRow = $this->repository->postEdit($id);
        if ($featuredRow) {
            return $this->getSuccessJsonResponse(['success' => 'Featured Rows Data Updated.']);
        } else {
            return $this->getErrorJsonResponse([], 'Featured Row Not Found');
        }
    }

    public function postDeletechannel($id)
    {
        $channelDelete = $this->repository->postDeletechannel($id);
        if ($channelDelete) {
            return $this->getSuccessJsonResponse(['success' => 'Featured Rows Channel Content Set Data Updated.']);
        } else {
            return $this->getErrorJsonResponse([], 'Featured Row Not Found');
        }
    }

    public function postDeleteTvshow($id)
    {
        $tvshowDelete = $this->repository->postDeleteTvshow($id);
        if ($tvshowDelete) {
            return $this->getSuccessJsonResponse(['success' => 'Featured Rows TvShow Content Set Data Updated.']);
        } else {
            return $this->getErrorJsonResponse([], 'Featured Row Not Found');
        }
    }

    public function postDeleteMovie($id)
    {
        $movieDelete = $this->repository->postDeleteMovie($id);
        if ($movieDelete) {
            return $this->getSuccessJsonResponse(['success' => 'Featured Rows Movie Content Set Data Updated.']);
        } else {
            return $this->getErrorJsonResponse([], 'Featured Row Not Found');
        }
    }
}
