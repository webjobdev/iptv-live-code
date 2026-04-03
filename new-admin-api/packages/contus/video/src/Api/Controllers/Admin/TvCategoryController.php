<?php

namespace Contus\Video\Api\Controllers\Admin;

use Contus\Base\ApiController;
use Contus\Base\Controller;
use Contus\Base\Repository;
use Contus\Video\Models\TvCategory;
use Contus\Video\Repositories\TvCategoryRepository;

class TvCategoryController extends ApiController
{

    public function __construct(TvCategoryRepository $tvCategoryRepository)
    {
        parent::__construct();
        $this->repository = $tvCategoryRepository;
        $this->repository->setRequestType(static::REQUEST_TYPE);
    }

    public function getInfo()
    {
        return $this->getSuccessJsonResponse(['success']);
    }

    public function postAdd()
    {
        $isCreated = false;
        if ($this->repository->CreateTvCatgory()) {
            $isCreated = true;
            return ($isCreated) ? $this->getSuccessJsonResponse(['message' => 'TV Category created successfully'])
                : $this->getErrorJsonResponse([], trans('subscribers::index.add.error'));
        }
    }

    public function getCategoryToEdit($id)
    {
        $cateroryId = $this->repository->getcaterory($id);
        return (is_null($cateroryId)) ?
            $this->getErrorJsonResponse([], null, 404) :
            $this->getSuccessJsonResponse(['message' => 'Tv Category Data Update Successfully.']);
    }

    public function postAddCategory()
    {
        $isCreated = false;
        if ($this->repository->postAddCategory()) {
            $isCreated = true;
            return ($isCreated) ? $this->getSuccessJsonResponse(['message' => 'Category Created Successfully.'])
                : $this->getErrorJsonResponse(['message' => 'Category Not Created.']);
        }
    }

    public function postEditCategory($id)
    {
        $cateroryId = $this->repository->postEditCategory($id);
        return (is_null($cateroryId)) ?
            $this->getErrorJsonResponse([], null, 404) :
            $this->getSuccessJsonResponse(['message' => 'Category Data Update Successfully.']);
    }

    public function postAddChannel()
    {
        $isCreated = false;
        if ($this->repository->postAddChannel()) {
            $isCreated = true;
            return ($isCreated) ? $this->getSuccessJsonResponse(['message' => 'Channel Add Successfully.'])
                : $this->getErrorJsonResponse(['message' => 'Channel Not Add.']);
        }
    }

    public function postEditChannel($id)
    {

    }

    public function fetchRecords()
    {
        return $this->getSuccessJsonResponse([
            'data' => $this->repository->fetchdata(),
        ]);
    }

    public function postDeleteCategory($id)
    {
        $cateroryId = $this->repository->categoryDelete($id);
        return (is_null($cateroryId)) ?
            $this->getErrorJsonResponse([], null, 404) :
            $this->getSuccessJsonResponse(['message' => 'Tv Category Data Update Successfully.']);
    }

    public function postDeleteChannel($id)
    {
        $cateroryId = $this->repository->channelDelete($id);
        return (is_null($cateroryId)) ?
            $this->getErrorJsonResponse([], null, 404) :
            $this->getSuccessJsonResponse(['message' => 'Tv Category Data Update Successfully.']);
    }
}
