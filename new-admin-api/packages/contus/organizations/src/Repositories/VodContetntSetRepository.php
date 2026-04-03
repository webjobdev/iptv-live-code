<?php

namespace Contus\Organizations\Repositories;

use Contus\Base\Helpers\StringLiterals;
use Contus\Base\Repository;
use Contus\Organizations\Model\OrganizationContentSets;
use Contus\Organizations\Model\OrganizationMonitizationPlan;
use Contus\Organizations\Model\VodContent;
use Contus\Vod\Model\VideoOnDemad;
use Illuminate\Support\Facades\Auth;

class VodContetntSetRepository extends Repository
{
    protected $_vodContent;

    public function __construct(VodContent $vodContent)
    {
        parent::__construct();
        $this->_vodContent = $vodContent;
    }

    public function postAdd()
    {
        return $this->postCreate($this->request->all());
    }

    public function postCreate($requestData)
    {

        $user = auth()->user();

        $this->setRules([
            'organization_id' => 'nullable',
            'name' => 'required',
            'description' => 'nullable',
            'is_active' => 'nullable',
            'monitization_type_buy' => 'nullable',
            'payment_method_buy' => 'nullable',
            'buy_price' => 'required',
            'monitization_type_rent' => 'nullable',
            'payment_method_rent' => 'nullable',
            'rent_price' => 'required',
            'currency' => 'nullable',
            'assigned_vod' => 'nullable',
            'cover_image' => 'nullable',
            'period' => 'required',
            'period_type' => 'nullable',
        ]);

        $this->_validate();

        $vod = new VodContent();

        if (isset($requestData['cover_image']) && $requestData['cover_image'] != '') {
            $thumbUrl = explode("/", $requestData['cover_image']);
            $fileName = $vod->getImageBaseName($thumbUrl[count($thumbUrl) - 1]);
            // $folderName = config("contus.base.image.posters.s3_location");
            $localStoragePath = config("app.url") . config("contus.base.image.posters.temporary_image_storage_path");
            // $s3BucketImgFilename = $this->uploadTos3Bucket($fileName, $folderName, $localStoragePath);
            // $s3BucketImgURL = $folderName . $s3BucketImgFilename;
            $localIamgePath = $localStoragePath . DIRECTORY_SEPARATOR . $fileName;
            $vod->cover_image = $localIamgePath;
            // Log::info('Processed poster image.', ['poster_path' => $localIamgePath]);;
        }

        $vod->organization_id = $requestData['organization_id'];
        $vod->name = $requestData['name'];
        $vod->description = $requestData['description'];
        $vod->is_active = $requestData['is_active'];
        $vod->monitization_type_buy = $requestData['monitization_type_buy'];
        $vod->payment_method_buy = $requestData['payment_method_buy'];
        $vod->buy_price = $requestData['buy_price'];
        $vod->monitization_type_rent = $requestData['monitization_type_rent'];
        $vod->payment_method_rent = $requestData['payment_method_rent'];
        $vod->rent_price = $requestData['rent_price'];
        $vod->currency = $requestData['currency'];
        $vod->assigned_vod = json_encode($requestData['assigned_vod']);
        $vod->by_user = $user->id;
        $vod->period = $requestData['period'];
        $vod->period_type = $requestData['period_type'];

        if ($vod->save()) {
            return 'success';
        } else {
            return 'error';
        }
    }

    public function postEdit($id)
    {
        $vodset = $this->_vodContent->find($id);

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
        if (isset($this->request->assigned_vodsets)) {
            $existingVod = json_decode($vodset->assigned_vodsets, true) ?? [];
            $newVod = json_decode(json_encode($this->request->assigned_vodsets), true);
            $merged = array_merge($existingVod, $newVod);
            $unique = [];
            foreach ($merged as $ch) {
                $unique[$ch['id']] = $ch;
            }
            $vodset->assigned_vodsets = json_encode(array_values($unique));
        }
        // if (isset($this->request->assigned_vodsets)) {
        //     $vodset->assigned_vod = json_encode($this->request->assigned_vod);
        // }
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
        $api = VodContent::with(['getorg', 'getuser'])
            ->paginate(10);

        $api->getCollection()->transform(function ($vodSet) {
            if (!empty($vodSet->assigned_vod)) {
                $assignedIds = collect(json_decode($vodSet->assigned_vod, true))
                    ->pluck('id')
                    ->toArray();

                if (!empty($assignedIds)) {
                    $vodSet->assigned_vod = VideoOnDemad::whereIn('id', $assignedIds)->get();
                } else {
                    $vodSet->assigned_vod = [];
                }
            }
            return $vodSet;
        });

        return $api;
    }

    public function prepareGrid()
    {
        $this->setGridModel($this->_vodContent)
            ->setEagerLoadingModels(['getorg', 'getuser']);
        return $this;
    }

    public function getGridHeadings()
    {
        return [
            'heading' => [
                ['name' => 'S.No', 'value' => 'SNo', 'sort' => false],
                ['name' => 'Set Name', 'value' => '', 'sort' => false],
                ['name' => 'Vod Qauntity', 'value' => '', 'sort' => false],
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
