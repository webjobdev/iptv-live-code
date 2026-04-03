<?php

namespace Contus\Organizations\Api\Controller\AppCustomization;

use Contus\Base\ApiController;
use Contus\Organizations\Repositories\AppCustomization\ChannelListingRepository;

class ChannelListingController extends ApiController
{
    public function __construct(ChannelListingRepository $channelListingRepository)
    {
        parent::__construct();
        $this->repository = $channelListingRepository;
        $this->repository->setRequestType(static::REQUEST_TYPE);
    }

    public function getInfo()
    {
        return $this->getSuccessJsonResponse(['success']);
    }

    public function postAdd()
    {
        $insert = $this->repository->postAdd();
        if ($insert == 'success') {
            return $this->getSuccessJsonResponse(['message' => 'Channel Listing Created Successfully.']);
        } else {
            return $this->getErrorJsonResponse([], $insert);
        }
    }


}