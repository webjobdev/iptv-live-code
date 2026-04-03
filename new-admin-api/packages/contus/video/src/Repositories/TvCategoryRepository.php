<?php

namespace Contus\Video\Repositories;

use Contus\Base\Helpers\StringLiterals;
use Contus\Base\Repository;
use Contus\Video\Models\CategoryChannelList;
use Contus\Video\Models\TvCategory;
use Contus\Video\Models\TvCategoryOrganizations;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TvCategoryRepository extends Repository
{
    /**
     * TvCategoryRepository constructor.
     */

    protected $_tvcategory;

    protected $_channelcategoryList;

    // public $channelcatry;

    public function __construct(TvCategory $tvCategory, CategoryChannelList $categoryChannelList)
    {
        parent::__construct();
        $this->_tvcategory = $tvCategory;
        $this->_channelcategoryList = $categoryChannelList;
    }

    // leval 1
    public function CreateTvCatgory()
    {
        $user = Auth::user();

        $this->setRules([
            'tv_categorie_name' => 'required',
            'organization' => 'required'
        ]);

        $this->_validate();

        $tvctry = new TvCategory();
        $tvctry->tv_categorie_name = $this->request->input('tv_categorie_name');
        // $tvctry->organization = $this->request->input('organization');

        $tvctry->save();

        foreach ($this->request->input('organization') as $orgId) {
            TvCategoryOrganizations::updateOrCreate(
                [
                    'tv_category_id' => $tvctry->id,
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
                'message' => 'Subscription added successfully.'
            ]
        );
    }

    public function getcaterory($id)
    {
        // dd($id);
        if (!empty($id)) {
            $ctry = $this->_tvcategory->findOrFail($id);

            $this->setRules([
                'tv_categorie_name' => 'required',
                'organization' => 'required'
            ]);

            $this->validate($this->request, $this->getRules());

            $ctry->tv_categorie_name = $this->request->tv_categorie_name;
            // $ctry->organization = $this->request->organization;

            $ctry->save();

            $user = Auth::user();

            foreach ($this->request->input('organization') as $orgId) {
                TvCategoryOrganizations::updateOrCreate(
                    [
                        'tv_category_id' => $ctry->id,
                        'organization_id' => $orgId
                    ],
                    [
                        'created_by' => $user->id
                    ]
                );
            }

            return response()->json([
                'success' => true,
                'message' => 'Channel Data Update Successfully.',
            ]);

        } else {
            return false;
        }
    }

    // leval 2
    public function postAddCategory()
    {
        $this->setRules([
            'categorie_id' => 'nullable|required',
            'category_name' => 'nullable|required',
            'category_order' => 'nullable|required'
        ]);

        $this->_validate();


        $catry = new TvCategory();

        $catry->categorie_id = $this->request->input('categorie_id');
        $catry->category_name = $this->request->input('category_name');
        $catry->category_order = $this->request->input('category_order');
        // $catry->channel = $channelcatry->id;

        $catry->save();

        return response()->json([
            'success' => true,
            'message' => 'Category Created Successfully.'
        ]);
    }

    public function postEditCategory($id)
    {
        if (!empty($id)) {
            $ctry = $this->_tvcategory->findOrFail($id);

            $this->setRules([
                'category_name' => 'nullable|required',
                'category_order' => 'nullable|required'
            ]);

            $this->validate($this->request, $this->getRules());

            // $ctry->categorie_id = $this->request->input('categorie_id');
            $ctry->category_name = $this->request->input('category_name');
            $ctry->category_order = $this->request->input('category_order');

            $ctry->save();

            return response()->json([
                'success' => true,
                'message' => 'Category Data Update Successfully.',
            ]);

        } else {
            return false;
        }
    }

    // leval 3
    public function postAddChannel()
    {
        $this->setRules([
            'sub_category_id' => 'nullable|required',
            'channel_id' => 'nullable|required',
        ]);

        $this->_validate();

        $user = Auth::user();

        foreach ($this->request->input('channel_id') as $chnl_id) {
            TvCategory::updateOrCreate(
                [
                    'sub_category_id' => $this->request->input('sub_category_id'),
                    'channel_id' => $chnl_id
                ],
                [
                    'created_by' => $user->id
                ]
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Channel Category Created Successfully.'
        ]);
    }

    public function categoryDelete($id)
    {
        $category = TvCategory::find($id);

        if (!$category) {
            return response()->json([
                'status' => 'error',
                'message' => 'Channel not found'
            ], 404);
        }

        $category->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Channel deleted and tv_category updated'
        ]);
    }

    public function channelDelete($id)
    {
        $channel = CategoryChannelList::find($id);

        if (!$channel) {
            return response()->json([
                'status' => 'error',
                'message' => 'Channel not found'
            ], 404);
        }

        TvCategory::where('channel', $id)->update(['channel' => null]);

        $channel->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Channel deleted and tv_category updated'
        ]);
    }

    public function fetchdata()
    {
        $rowsPerPage = $this->request->get('rowsPerPage');
        $data = TvCategory::with([
            'categorie_id' => function ($q) {
                $q->with([
                    'get_sub_category' => function ($sub) {
                        $sub->with('getChannel');
                    }
                ]);
            },
            'getOrganization'
        ])
            ->whereNull('categorie_id')
            ->whereNull('sub_category_id')
            ->whereNull('channel_id')
            ->orderBy('id', 'desc')
            ->paginate($rowsPerPage);

        return $data;
    }



    public function prepareGrid()
    {
        $this->setGridModel($this->_tvcategory)
            ->setEagerLoadingModels(['getOrganization', 'getChannel']);
        return $this;
    }

    public function getGridHeadings()
    {
        return [
            'heading' => [
                ['name' => 'S.No', 'value' => 'SNo', 'sort' => false],
                ['name' => 'Name', 'value' => '', 'sort' => true],
                ['name' => 'Categories', 'value' => '', 'sort' => false],
                ['name' => 'Organizations', 'value' => '', 'sort' => false],
                ['name' => 'Actions', 'value' => '', 'sort' => false],
            ]
        ];
    }

    protected function searchFilter($builderCoupon)
    {
        $searchRecordUsers = $this->request->has(StringLiterals::SEARCHRECORD) && is_array($this->request->input(StringLiterals::SEARCHRECORD)) ? $this->request->input(StringLiterals::SEARCHRECORD) : [];
        foreach ($searchRecordUsers as $key => $value) {
            if ($key == 'is_active' && $value == 'all') {
                continue;
            }
            $builderCoupon = $builderCoupon->where($key, 'like', "%$value%");
        }
        return $builderCoupon;
    }

    public function gridDelete($ids)
    {
        $ids = is_array($ids) ? $ids : [$ids];
        $isDeleted = false;

        if (!empty($ids)) {
            $categories = $this->_tvcategory->whereIn('id', $ids)->get();

            foreach ($categories as $category) {
                $category->delete();
            }
            $isDeleted = true;
        }

        return $isDeleted;
    }
}
