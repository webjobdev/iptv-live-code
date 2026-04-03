<?php

namespace Contus\Video\Api\Controllers\Admin;

use Contus\Base\ApiController;
use Contus\Base\Controller;
use Contus\Video\Repositories\SeriesCategoryRepository;

class SeriesCategoryController extends ApiController
{

    public function __construct(SeriesCategoryRepository $seriesCategoryRepository)
    {
        parent::__construct();
        $this->repository = $seriesCategoryRepository;
        $this->repository->setRequestType(static::REQUEST_TYPE);
    }

    public function getInfo()
    {
        return $this->getSuccessJsonResponse(['success']);
    }

    public function fetchRecords()
    {
        return $this->getSuccessJsonResponse([
            'data' => $this->repository->fetchdata($this->request),
        ]);
    }

    public function postAdd()
    {
        $isCreated = false;
        if ($this->repository->CreateSeriesCategory()) {
            $isCreated = true;
            return ($isCreated) ? $this->getSuccessJsonResponse(['message' => 'Vod Category created successfully'])
                : $this->getErrorJsonResponse([], trans('subscribers::index.add.error'));
        }
    }

    public function postCategoryEdit($id)
    {
        $cateroryId = $this->repository->getcaterory($id);
        return (is_null($cateroryId)) ?
            $this->getErrorJsonResponse([], null, 404) :
            $this->getSuccessJsonResponse(['message' => 'Vod Category Data Update Successfully.']);
    }

    public function postAddCategory()
    {
        $isCreated = false;
        if ($this->repository->addCategorie()) {
            $isCreated = true;
            return ($isCreated) ? $this->getSuccessJsonResponse(['message' => 'Category Data Add.'])
                : $this->getErrorJsonResponse([], 'Category Not Add.');
        }
    }

    public function getCategoryToEdit($id)
    {
        $cateroryId = $this->repository->editCategorie($id);
        return (is_null($cateroryId)) ?
            $this->getErrorJsonResponse([], null, 404) :
            $this->getSuccessJsonResponse(['message' => 'Vod Category Data Update Successfully.']);
    }

    public function addSubCategory()
    {
        $isCreated = false;
        if ($this->repository->addSubCategory()) {
            $isCreated = true;
            return ($isCreated) ? $this->getSuccessJsonResponse(['message' => 'Channel Add Successfully.'])
                : $this->getErrorJsonResponse(['message' => 'Channel Not Add.']);
        }
    }

    public function postDeleteCategory($id)
    {
        $cateroryId = $this->repository->categoryDelete($id);
        return (is_null($cateroryId)) ?
            $this->getErrorJsonResponse([], null, 404) :
            $this->getSuccessJsonResponse(['message' => 'Vod Category Data Update Successfully.']);
    }

    public function postDeleteSubCtgry($id)
    {
        $cateroryId = $this->repository->SubCtgryDelete($id);
        return (is_null($cateroryId)) ?
            $this->getErrorJsonResponse([], null, 404) :
            $this->getSuccessJsonResponse(['message' => 'Vod Category Data Update Successfully.']);
    }

    public function getRecords()
    {
        return $this->getSuccessJsonResponse([
            'data' => $this->repository->getdata(),
        ]);
    }

}
