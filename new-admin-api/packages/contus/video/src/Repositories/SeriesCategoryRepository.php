<?php

namespace Contus\Video\Repositories;

use Contus\Base\Helpers\StringLiterals;
use Contus\Base\Repository;
use Contus\Video\Models\SeriesCategoryOrganizations;
use Contus\Video\Models\SubSeriesCategory;
use Contus\Video\Models\SeriesCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SeriesCategoryRepository extends Repository
{
    /**
     * TvCategoryRepository constructor.
     */

    protected $_seriesCategory;
    protected $_subseriesCategory;

    public function __construct(SeriesCategory $seriesCategory, SubSeriesCategory $subSeriesCategory)
    {
        parent::__construct();
        $this->_seriesCategory = $seriesCategory;
        $this->_subseriesCategory = $subSeriesCategory;
    }

    // level 1
    public function CreateSeriesCategory()
    {
        $this->setRules([
            'series_categorie_name' => 'required',
            'organization' => 'required',
        ]);

        $this->_validate();

        $vodctgry = new SeriesCategory();

        $vodctgry->series_categorie_name = $this->request->input('series_categorie_name');
        // $vodctgry->organization = $this->request->input('organization');

        $vodctgry->save();

        $user = Auth::user();

        foreach ($this->request->input('organization') as $orgId) {
            SeriesCategoryOrganizations::updateOrCreate(
                [
                    'series_category_id' => $vodctgry->id,
                    'organization_id' => $orgId
                ],
                [
                    'created_by' => $user->id
                ]
            );
        }

        return response()->json(
            [
                'success' => true,
                'message' => 'Vod Category Add Successfully.'
            ]
        );
    }

    public function getcaterory($id)
    {
        if (!empty($id)) {
            $vodctgry = $this->_seriesCategory->findOrFail($id);

            $this->setRules([
                'series_categorie_name' => 'required',
                'organization' => 'required',
            ]);

            $this->validate($this->request, $this->getCount());

            $vodctgry->series_categorie_name = $this->request->input('series_categorie_name');
            // $vodctgry->organization = $this->request->input('organization');

            $vodctgry->save();

            $user = Auth::user();

            foreach ($this->request->input('organization') as $orgId) {
                SeriesCategoryOrganizations::updateOrCreate(
                    [
                        'series_category_id' => $vodctgry->id,
                        'organization_id' => $orgId
                    ],
                    [
                        'created_by' => $user->id
                    ]
                );
            }

            return response()->json([
                'success' => true,
                'message' => 'Vod Category Data Updated.'
            ]);

        } else {
            return false;
        }
    }

    // level 2
    public function addCategorie()
    {
        $this->setRules([
            'categorie_id' => 'required',
            'category_name' => 'required',
            'category_order' => 'required',
        ]);

        $this->_validate();

        $ctgry = new SeriesCategory();

        $ctgry->categorie_id = $this->request->input('categorie_id');
        $ctgry->category_name = $this->request->input('category_name');
        $ctgry->category_order = $this->request->input('category_order');

        $ctgry->save();

        return response()->json([
            'success' => true,
            'message' => 'Category Data Add SuccessFully.'
        ]);
    }

    public function editCategorie($id)
    {
        if (!empty($id)) {
            $ctgry = $this->_seriesCategory->findOrFail($id);

            $this->setRules([
                'category_name' => 'required',
                'category_order' => 'required',
            ]);

            $this->validate($this->request, $this->getCount());

            $ctgry->category_name = $this->request->input('category_name');
            $ctgry->category_order = $this->request->input('category_order');

            $ctgry->save();

            return response()->json([
                'success' => true,
                'message' => 'Vod Category Data Updated.'
            ]);

        } else {
            return false;
        }
    }

    // level 3
    public function addSubCategory()
    {
        $this->setRules([
            'sub_category_id' => 'required|integer',
            'category_name' => 'required|string',
            'category_order' => 'required|integer',
        ]);

        $this->_validate();

        $Subctgry = new SeriesCategory();

        $Subctgry->sub_category_id = $this->request->input('sub_category_id');
        $Subctgry->category_name = $this->request->input('category_name');
        $Subctgry->category_order = $this->request->input('category_order');

        $Subctgry->save();

        return response()->json([
            'success' => true,
            'message' => 'Channel Sub Sub Category Created Successfully.'
        ]);
    }


    public function categoryDelete($id)
    {
        $category = SeriesCategory::find($id);

        if (!$category) {
            return response()->json([
                'status' => 'error',
                'message' => 'Vod not found'
            ], 404);
        }

        // TvCategory::where('channel', $id)->update(['channel' => null]);

        $category->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Vod deleted and tv_category updated'
        ]);
    }

    public function SubCtgryDelete($id)
    {
        $SubCtgry = SubSeriesCategory::find($id);

        if (!$SubCtgry) {
            return response()->json([
                'status' => 'error',
                'message' => 'Vod not found'
            ], 404);
        }

        SeriesCategory::where('sub_category_id', $id)->update(['sub_category_id' => null]);

        $SubCtgry->delete(); 

        return response()->json([
            'status' => 'success',
            'message' => 'Vod deleted and tv_category updated'
        ]);
    }

    public function fetchdata(Request $request)
    {
        Log::info($request);
        $fetchData = SeriesCategory::with(['categories.getSubCategory', 'getOrganization'])
            ->whereNull('categorie_id')
            ->whereNull('sub_category_id')
            ->orderBy('id', 'desc')
            ->paginate($request->input('rowsPerPage', 10));
        return $fetchData;
    }

    public function getdata()
    {
        $data = SeriesCategory::with(['categories.getSubCategory'])
            ->get();
        return $data;
    }

    public function prepareGrid()
    {
        $this->setGridModel($this->_seriesCategory);
        return $this;
    }

    public function getGridHeadings()
    {
        return [
            'heading' => [
                ['name' => 'S.No', 'value' => 'SNo', 'sort' => false],
                ['name' => 'Name', 'value' => '', 'sort' => false],
                ['name' => 'Categories', 'value' => '', 'sort' => false],
                ['name' => 'Sub Categories', 'value' => '', 'sort' => false],
                ['name' => 'Organizations', 'value' => '', 'sort' => false],
                ['name' => 'Actions', 'value' => '', 'sort' => false],
            ]
        ];
    }

    protected function searchFilter($builderCategories)
    {
        $searchRecordCategories = $this->request->has(StringLiterals::SEARCHRECORD) && is_array($this->request->input(StringLiterals::SEARCHRECORD)) ? $this->request->input(StringLiterals::SEARCHRECORD) : [];
        $title = $is_active = null;
        extract($searchRecordCategories);
        if ($title) {
            $builderCategories = $builderCategories->where(StringLiterals::TITLE, 'like', '%' . $title . '%');
        }
        if (is_numeric($is_active)) {
            $builderCategories = $builderCategories->where(StringLiterals::ISACTIVE, $is_active);
        }

        return $builderCategories;
    }
}