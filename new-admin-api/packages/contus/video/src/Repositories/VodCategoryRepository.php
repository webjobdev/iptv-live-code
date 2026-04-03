<?php

namespace Contus\Video\Repositories;

use Contus\Base\Helpers\StringLiterals;
use Contus\Base\Repository;
use Contus\Video\Models\SubVodCategory;
use Contus\Video\Models\VodCategory;
use Contus\Video\Models\VodCategoryOrganizations;
use Illuminate\Support\Facades\Auth;

class VodCategoryRepository extends Repository
{
    /**
     * TvCategoryRepository constructor.
     */

    protected $_vodcategory;
    protected $_subvodcategory;

    public function __construct(VodCategory $vodCategory, SubVodCategory $subVodCategory)
    {
        parent::__construct();
        $this->_vodcategory = $vodCategory;
        $this->_subvodcategory = $subVodCategory;
    }

    // level 1
    public function CreateVodCategory()
    {
        $this->setRules([
            'vod_categorie_name' => 'required',
            'organization' => 'required',
        ]);

        $this->_validate();

        $vodctgry = new VodCategory();

        $vodctgry->vod_categorie_name = $this->request->input('vod_categorie_name');
        // $vodctgry->organization = $this->request->input('organization');

        $vodctgry->save();

        $user = Auth::user();

        foreach ($this->request->input('organization') as $orgId) {
            VodCategoryOrganizations::updateOrCreate(
                [
                    'vod_category_id' => $vodctgry->id,
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
            $vodctgry = $this->_vodcategory->findOrFail($id);

            $this->setRules([
                'vod_categorie_name' => 'required',
                'organization' => 'required',
            ]);

            $this->validate($this->request, $this->getCount());

            $vodctgry->vod_categorie_name = $this->request->input('vod_categorie_name');
            // $vodctgry->organization = $this->request->input('organization');

            $vodctgry->save();

            $user = Auth::user();

            foreach ($this->request->input('organization') as $orgId) {
                VodCategoryOrganizations::updateOrCreate(
                    [
                        'vod_category_id' => $vodctgry->id,
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

        $ctgry = new VodCategory();

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
            $ctgry = $this->_vodcategory->findOrFail($id);

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
            'sub_category_id' => 'nullable|required',
            'category_name' => 'nullable|required',
            'category_order' => 'required',
        ]);

        $this->_validate();

        $Subctgry = new VodCategory();

        $Subctgry->sub_category_id = $this->request->input('sub_category_id');
        $Subctgry->category_name = $this->request->input('category_name');
        $Subctgry->category_order = $this->request->input('category_order');

        $Subctgry->save();

        // $catry = VodCategory::find($this->request->input('categorie_id'));
        // if ($catry) {
        //     $catry->sub_category_id = $Subctgry->id; // store mapping id
        //     $catry->save();
        // }

        return response()->json([
            'success' => true,
            'message' => 'Vod Sub Category Created Successfully.'
        ]);
    }

    public function categoryDelete($id)
    {
        $category = VodCategory::find($id);

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
        $SubCtgry = SubVodCategory::find($id);

        if (!$SubCtgry) {
            return response()->json([
                'status' => 'error',
                'message' => 'Vod not found'
            ], 404);
        }

        VodCategory::where('sub_category_id', $id)->update(['sub_category_id' => null]);

        $SubCtgry->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Vod deleted and tv_category updated'
        ]);
    }


    public function fetchdata()
    {
        $rowsPerPage = $this->request->get('rowsPerPage');
        $fetchData = VodCategory::with(['categories.getSubCategory', 'getOrganization'])
            ->whereNull('sub_category_id')
            ->whereNull('categorie_id')
            ->orderBy('id', 'desc')
            ->paginate($rowsPerPage);

        return $fetchData;
    }

    public function getdata()
    {
        $data = VodCategory::with(['categories.getSubCategory', 'getOrganization'])
            ->whereNull('sub_category_id')
            ->whereNull('categorie_id')
            ->get();
        return $data;
    }

    public function prepareGrid()
    {
        $this->setGridModel($this->_vodcategory);
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
