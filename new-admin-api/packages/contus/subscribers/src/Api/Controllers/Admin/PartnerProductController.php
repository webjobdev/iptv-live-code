<?php 

namespace Contus\Subscribers\Api\Controllers\Admin;

use Contus\Base\ApiController;
use Contus\Subscribers\Repositories\PartnerProductRepository;


class PartnerProductController extends ApiController{

    public function __construct(PartnerProductRepository $partnerProductRepository) {
        parent::__construct();
        $this->repository = $partnerProductRepository;
        $this->repository->setRequestType(static::REQUEST_TYPE);
    }

    public function getInfo() {
        return $this->getSuccessJsonResponse(['success']);
    }
}