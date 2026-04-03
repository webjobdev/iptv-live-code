<?php

namespace Contus\Organizations\Api\Controller;

use Contus\Base\ApiController;
use Contus\Organizations\Repositories\OrganizationsAccessoriesRepository;
use Illuminate\Support\Facades\Auth;

class OrganizationsAccessoriesController extends ApiController
{

    public function __construct(OrganizationsAccessoriesRepository $organizationsAccessoriesRepository)
    {
        parent::__construct();
        $this->repository = $organizationsAccessoriesRepository;
        $this->repository->setRequestType(static::REQUEST_TYPE);
    }

    public function getInfo()
    {
        $user = Auth::user();
        return $this->getSuccessJsonResponse(['success']);
    }

    public function postAdd()
    {
        $acs = $this->repository->postAdd();
        if ($acs == 'success') {
            return $this->getSuccessJsonResponse(['success => Accessories Data Created.']);
        } else {
            return $this->getErrorJsonResponse([], $acs);
        }
    }

    public function postEdit($id)
    {
        $scdedit = $this->repository->postEdit($id);
        return (is_null($scdedit)) ?
            $this->getErrorJsonResponse([], null, 404) :
            $this->getSuccessJsonResponse(['response' => $scdedit]);
    }

    public function toggleEdit($id)
    {
        $scdedit = $this->repository->toggleEdit($id);
        return (is_null($scdedit)) ?
            $this->getErrorJsonResponse([], null, 404) :
            $this->getSuccessJsonResponse(['response' => $scdedit]);
    }
}
