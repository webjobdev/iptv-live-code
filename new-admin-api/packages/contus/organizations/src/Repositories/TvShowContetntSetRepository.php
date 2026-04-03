<?php

namespace Contus\Organizations\Repositories;

use Contus\Base\Helpers\StringLiterals;
use Contus\Base\Repository;
use Contus\Organizations\Model\OrganizationContentSets;
use Contus\Organizations\Model\OrganizationMonitizationPlan;
use Contus\Organizations\Model\TvShowContent;
use Contus\Tvshow\Model\TvShow;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TvShowContetntSetRepository extends Repository
{
    protected $_tvshowContentSet;

    public function __construct(TvShowContent $tvshowcontent)
    {
        parent::__construct();
        $this->_tvshowContentSet = $tvshowcontent;
    }

    // public function postAdd()
    // {
    //     return $this->postCreate($this->request->all());
    // }

    // public function postCreate($requestData)
    // {

    //     $user = auth()->user();

    //     $this->setRules([
    //         'organization_id' => 'nullable',
    //         'name' => 'required',
    //         'description' => 'nullable',
    //         'item_type' => 'nullable',
    //         'is_active' => 'nullable',
    //         'monitization_type_buy' => 'nullable',
    //         'payment_method_buy' => 'nullable',
    //         'buy_price' => 'required',
    //         'monitization_type_rent' => 'nullable',
    //         'payment_method_rent' => 'nullable',
    //         'rent_price' => 'required',
    //         'currency' => 'nullable',
    //         'assigned_tv_show' => 'nullable',
    //         'cover_image' => 'nullable',
    //         'period' => 'required',
    //         'period_type' => 'nullable',
    //     ]);

    //     $this->_validate();

    //     $tvs = new TvShowContent();

    //     if (isset($requestData['cover_image']) && $requestData['cover_image'] != '') {
    //         $thumbUrl = explode("/", $requestData['cover_image']);
    //         $fileName = $tvs->getImageBaseName($thumbUrl[count($thumbUrl) - 1]);
    //         // $folderName = config("contus.base.image.posters.s3_location");
    //         $localStoragePath = config("app.url") . config("contus.base.image.posters.temporary_image_storage_path");
    //         // $s3BucketImgFilename = $this->uploadTos3Bucket($fileName, $folderName, $localStoragePath);
    //         // $s3BucketImgURL = $folderName . $s3BucketImgFilename;
    //         $localIamgePath = $localStoragePath . DIRECTORY_SEPARATOR . $fileName;
    //         $tvs->cover_image = $localIamgePath;
    //         // Log::info('Processed poster image.', ['poster_path' => $localIamgePath]);;
    //     }

    //     $tvs->organization_id = $requestData['organization_id'];
    //     $tvs->name = $requestData['name'];
    //     $tvs->item_type = $requestData['item_type'];
    //     $tvs->description = $requestData['description'];
    //     $tvs->is_active = $requestData['is_active'];
    //     $tvs->monitization_type_buy = $requestData['monitization_type_buy'];
    //     $tvs->payment_method_buy = $requestData['payment_method_buy'];
    //     $tvs->buy_price = $requestData['buy_price'];
    //     $tvs->monitization_type_rent = $requestData['monitization_type_rent'];
    //     $tvs->payment_method_rent = $requestData['payment_method_rent'];
    //     $tvs->rent_price = $requestData['rent_price'];
    //     $tvs->currency = $requestData['currency'];
    //     $tvs->assigned_tv_show = json_encode($requestData['assigned_tv_show']);
    //     $tvs->assigned_tv_show_season = json_encode($requestData['assigned_tv_show_season']);
    //     $tvs->assigned_tv_show_episode = json_encode($requestData['assigned_tv_show_episode']);
    //     $tvs->by_user = $user->id;
    //     $tvs->period = $requestData['period'];
    //     $tvs->period_type = $requestData['period_type'];

    //     if ($tvs->save()) {
    //         return 'success';
    //     } else {
    //         return 'error';
    //     }
    // }
    public function postAdd()
    {
        return $this->postCreate($this->request->all());
    }

    public function postCreate(array $requestData)
    {
        $user = auth()->user();

        $this->setRules([
            'organization_id' => 'nullable',
            'name' => 'required',
            'description' => 'nullable',
            'item_type' => 'nullable',
            'is_active' => 'nullable',
            'monitization_type_buy' => 'nullable',
            'payment_method_buy' => 'nullable',
            'buy_price' => 'required',
            'monitization_type_rent' => 'nullable',
            'payment_method_rent' => 'nullable',
            'rent_price' => 'required',
            'currency' => 'nullable',
            'assigned_tv_show' => 'nullable',
            'period' => 'required',
            'period_type' => 'nullable',
        ]);

        $this->_validate();

        // ✅ Handle cover image safely
        if (!empty($requestData['cover_image'])) {
            $tvsTemp = new TvShowContent();

            $thumbUrl = explode("/", $requestData['cover_image']);
            $fileName = $tvsTemp->getImageBaseName(end($thumbUrl));

            $localStoragePath = config("app.url") . config("contus.base.image.posters.temporary_image_storage_path");

            $requestData['cover_image'] = $localStoragePath . DIRECTORY_SEPARATOR . $fileName;
        }

        // ✅ Safe defaults for all fields (prevents undefined index)
        $requestData = array_merge([
            'organization_id' => null,
            'name' => null,
            'item_type' => null,
            'description' => null,
            'is_active' => null,
            'monitization_type_buy' => null,
            'payment_method_buy' => null,
            'buy_price' => 0,
            'monitization_type_rent' => null,
            'payment_method_rent' => null,
            'rent_price' => 0,
            'currency' => null,
            'period' => null,
            'period_type' => null,

            // ✅ JSON defaults
            'assigned_tv_show' => [],
            'assigned_tv_show_season' => [],
            'assigned_tv_show_episode' => [],
        ], $requestData);

        // ✅ Encode JSON safely
        $requestData['assigned_tv_show'] = json_encode($requestData['assigned_tv_show']);
        $requestData['assigned_tv_show_season'] = json_encode($requestData['assigned_tv_show_season']);
        $requestData['assigned_tv_show_episode'] = json_encode($requestData['assigned_tv_show_episode']);

        // ✅ Add user
        $requestData['by_user'] = $user->id;

        // ✅ Create
        $tvs = TvShowContent::create($requestData);

        return $tvs ? 'success' : 'error';
    }

    
    public function postEdit($id)
    {
        $vodset = $this->_tvshowContentSet->find($id);
        Log::info($vodset);

        // if (!$vodset) {
        //     return 'error';
        // }

        $this->setRules([
            'organization_id' => 'nullable',
            'name' => 'nullable',
            'description' => 'nullable',
            'is_active' => 'nullable',
            'monitization_type' => 'nullable',
            'payment_method' => 'nullable',
            'price' => 'nullable',
            'currency' => 'nullable',
            'assigned_vod' => 'nullable',
            // 'cover_image' => 'required',
        ]);

        $this->validate($this->request, $this->getRules());

        if (isset($this->request->cover_image)) {
            $thumbUrl = explode("/", $this->request->cover_image);
            $fileName = $vodset->getImageBaseName($thumbUrl[count($thumbUrl) - 1]);
            // $folderName = config("contus.base.image.posters.s3_location");
            $localStoragePath = config("app.url") . config("contus.base.image.posters.temporary_image_storage_path");
            // $s3BucketImgFilename = $this->uploadTos3Bucket($fileName, $folderName, $localStoragePath);
            // $s3BucketImgURL = $folderName . $s3BucketImgFilename;
            $localIamgePath = $localStoragePath . DIRECTORY_SEPARATOR . $fileName;
            $vodset->cover_image = $localIamgePath;
            // Log::info('Processed poster image.', ['poster_path' => $localIamgePath]);;
        }

        if (isset($this->request->organization_id)) {
            $vodset->organization_id = $this->request->organization_id;
        }
        if (isset($this->request->name)) {
            $vodset->name = $this->request->name;
        }
        if (isset($this->request->item_type)) {
            $vodset->item_type = $this->request->item_type;
        }
        if (isset($this->request->description)) {
            $vodset->description = $this->request->description;
        }
        if (isset($this->request->monitization_type_buy)) {
            $vodset->monitization_type_buy = $this->request->monitization_type_buy;
        }
        if (isset($this->request->payment_method_buy)) {
            $vodset->payment_method_buy = $this->request->payment_method_buy;
        }
        if (isset($this->request->buy_price)) {
            $vodset->buy_price = $this->request->buy_price;
        }
        if (isset($this->request->monitization_type_rent)) {
            $vodset->monitization_type_rent = $this->request->monitization_type_rent;
        }
        if (isset($this->request->payment_method_rent)) {
            $vodset->payment_method_rent = $this->request->payment_method_rent;
        }
        if (isset($this->request->rent_price)) {
            $vodset->rent_price = $this->request->rent_price;
        }
        if (isset($this->request->currency)) {
            $vodset->currency = $this->request->currency;
        }

        if (isset($this->request->assigned_tv_show)) {
            $existing_tv_show = json_decode($vodset->assigned_tv_show, true) ?? [];
            $new_tv_show = json_decode(json_encode($this->request->assigned_tv_show), true);
            $merged = array_merge($existing_tv_show, $new_tv_show);
            $unique = [];
            foreach ($merged as $ch) {
                $unique[$ch['id']] = $ch;
            }
            $vodset->assigned_tv_show = json_encode(array_values($unique));
        }

        if (isset($this->request->assigned_tv_show_season)) {
            $existingtv_show_season = json_decode($vodset->assigned_tv_show_season, true) ?? [];
            $newtv_show_season = json_decode(json_encode($this->request->assigned_tv_show_season), true);
            $merged = array_merge($existingtv_show_season, $newtv_show_season);
            $unique = [];
            foreach ($merged as $ch) {
                $unique[$ch['id']] = $ch;
            }
            $vodset->assigned_tv_show_season = json_encode(array_values($unique));
        }

        if (isset($this->request->assigned_tv_show_episode)) {
            $existingtv_show_episode = json_decode($vodset->assigned_tv_show_episode, true) ?? [];
            $newtv_show_episode = json_decode(json_encode($this->request->assigned_tv_show_episode), true);
            $merged = array_merge($existingtv_show_episode, $newtv_show_episode);
            $unique = [];
            foreach ($merged as $ch) {
                $unique[$ch['id']] = $ch;
            }
            $vodset->assigned_tv_show_episode = json_encode(array_values($unique));
        }

        if (isset($this->request->is_active)) {
            $vodset->is_active = !empty($this->request->is_active) ? 1 : 0;
        }
        if (isset($this->request->period)) {
            $vodset->period = $this->request->period;
        }
        if (isset($this->request->period_type)) {
            $vodset->period_type = $this->request->period_type;
        }
        $vodset->by_user = Auth::user()->id;

        if ($vodset->save()) {
            return 'success';
        } else {
            return 'error';
        }
    }

    public function fetchRecords()
    {
        $api = TvShowContent::with(['getorg', 'getuser'])
            ->paginate(15);

        $api->getCollection()->transform(function ($vodSet) {
            if (!empty($vodSet->assigned_tv_show)) {
                $assignedIds = collect(json_decode($vodSet->assigned_tv_show, true))
                    ->pluck('id')
                    ->toArray();

                if (!empty($assignedIds)) {
                    $vodSet->assigned_tv_show = TvShow::whereIn('id', $assignedIds)->get();
                    dd($vodSet->assigned_tv_show);
                } else {
                    $vodSet->assigned_tv_show = [];
                }
            }
            return $vodSet;
        });

        return $api;
    }

    public function prepareGrid()
    {
        $this->setGridModel($this->_tvshowContentSet)
            ->setEagerLoadingModels(['getorg', 'getuser']);
        // dd($this);
        return $this;
    }

    public function getGridHeadings()
    {
        return [
            'heading' => [
                ['name' => 'S.No', 'value' => 'SNo', 'sort' => false],
                ['name' => 'Set Name', 'value' => '', 'sort' => false],
                ['name' => 'Live Event Qauntity', 'value' => '', 'sort' => false],
                ['name' => 'Listed', 'value' => '', 'sort' => false],
                ['name' => 'Monitization Type', 'value' => '', 'sort' => false],
                ['name' => 'Payment Method', 'value' => '', 'sort' => false],
                ['name' => 'Price', 'value' => '', 'sort' => false],
                ['name' => 'By User', 'value' => '', 'sort' => false],
                ['name' => 'Updated', 'value' => '', 'sort' => false],
                ['name' => 'Actions', 'value' => '', 'sort' => false],
            ]
        ];
    }

    protected function searchFilter($builderCoupon)
    {
        $searchRecordUsers = $this->request->has(StringLiterals::SEARCHRECORD)
            && is_array($this->request->input(StringLiterals::SEARCHRECORD))
            ? $this->request->input(StringLiterals::SEARCHRECORD)
            : [];

        foreach ($searchRecordUsers as $key => $value) {
            if (in_array($key, ['is_active', 'is_parental']) && $value === 'all') {
                continue;
            }

            $builderCoupon = $builderCoupon->where($key, 'like', "%$value%");
        }

        return $builderCoupon;
    }
}
