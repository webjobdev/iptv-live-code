<?php

namespace Contus\Organizations\Repositories;

use Contus\Base\Helpers\StringLiterals;
use Contus\Base\Repository;
use Contus\Organizations\Model\LiveEventContent;
use Contus\Organizations\Model\OrganizationContentSets;
use Contus\Organizations\Model\OrganizationMonitizationPlan;
use Illuminate\Support\Facades\Auth;

class LiveEventContetntSetRepository extends Repository
{
    protected $_liveset;

    public function __construct(LiveEventContent $liveEventset)
    {
        parent::__construct();
        $this->_liveset = $liveEventset;
    }

    public function Eventset()
    {
        return $this->postCreate($this->request->all());
    }

    public function postCreate($requestData)
    {
        $user = Auth::user();
        $this->setRules([
            'organization_id' => 'nullable',
            'name' => 'required',
            'description' => 'nullable',
            'is_active' => 'nullable',
            'monitization_type' => 'nullable',
            'payment_method' => 'nullable',
            'price' => 'required',
            'currency' => 'nullable',
            'assigned_channels' => 'nullable',
            'cover_image' => 'nullable',
        ]);

        $this->_validate();

        $liveEventContentSet = new LiveEventContent();

        if (isset($requestData['cover_image']) && $requestData['cover_image'] != '') {
            $thumbUrl = explode("/", $requestData['cover_image']);
            $fileName = $liveEventContentSet->getImageBaseName($thumbUrl[count($thumbUrl) - 1]);
            // $folderName = config("contus.base.image.posters.s3_location");
            $localStoragePath = config("app.url") . config("contus.base.image.posters.temporary_image_storage_path");
            // $s3BucketImgFilename = $this->uploadTos3Bucket($fileName, $folderName, $localStoragePath);
            // $s3BucketImgURL = $folderName . $s3BucketImgFilename;
            $localIamgePath = $localStoragePath . DIRECTORY_SEPARATOR . $fileName;
            $liveEventContentSet->cover_image = $localIamgePath;
            // Log::info('Processed poster image.', ['poster_path' => $localIamgePath]);;
        }

        $liveEventContentSet->organization_id = $requestData['organization_id'];
        $liveEventContentSet->name = $requestData['name'];
        $liveEventContentSet->description = $requestData['description'];
        $liveEventContentSet->is_active = isset($requestData['is_active']) ? 1 : 0;
        $liveEventContentSet->monitization_type = $requestData['monitization_type'];
        $liveEventContentSet->payment_method = $requestData['payment_method'];
        $liveEventContentSet->price = $requestData['price'];
        $liveEventContentSet->currency = $requestData['currency'];
        $liveEventContentSet->by_user = $user->id;
        $liveEventContentSet->assigned_channels = json_encode($requestData['assigned_channels']);

        if ($liveEventContentSet->save()) {
            return 'success';
        } else {
            return 'error';
        }
    }


    public function postEdit($id)
    {
        $channel = $this->_liveset->find($id);

        $this->setRules([
            'organization_id' => 'nullable',
            'name' => 'nullable',
            'description' => 'nullable',
            'is_active' => 'nullable',
            'monitization_type' => 'nullable',
            'payment_method' => 'nullable',
            'price' => 'nullable',
            'currency' => 'nullable',
            'assigned_channels' => 'nullable',
            // 'cover_image' => 'required',
        ]);

        $this->validate($this->request, $this->getRules());

        if (isset($this->request->cover_image)) {
            $thumbUrl = explode("/", $this->request->cover_image);
            $fileName = $channel->getImageBaseName($thumbUrl[count($thumbUrl) - 1]);
            // $folderName = config("contus.base.image.posters.s3_location");
            $localStoragePath = config("app.url") . config("contus.base.image.posters.temporary_image_storage_path");
            // $s3BucketImgFilename = $this->uploadTos3Bucket($fileName, $folderName, $localStoragePath);
            // $s3BucketImgURL = $folderName . $s3BucketImgFilename;
            $localIamgePath = $localStoragePath . DIRECTORY_SEPARATOR . $fileName;
            $channel->cover_image = $localIamgePath;
            // Log::info('Processed poster image.', ['poster_path' => $localIamgePath]);;
        }

        if (isset($this->request->organization_id)) {
            $channel->organization_id = $this->request->organization_id;
        }
        if (isset($this->request->name)) {
            $channel->name = $this->request->name;
        }
        if (isset($this->request->description)) {
            $channel->description = $this->request->description;
        }
        if (isset($this->request->monitization_type)) {
            $channel->monitization_type = $this->request->monitization_type;
        }
        if (isset($this->request->payment_method)) {
            $channel->payment_method = $this->request->payment_method;
        }
        if (isset($this->request->price)) {
            $channel->price = $this->request->price;
        }
        if (isset($this->request->currency)) {
            $channel->currency = $this->request->currency;
        }
        if (isset($this->request->assigned_channels)) {
            $existingLiveEvent = json_decode($channel->assigned_channels, true) ?? [];
            $newLiveEvent = json_decode(json_encode($this->request->assigned_channels), true);
            $merged = array_merge($existingLiveEvent, $newLiveEvent);
            $unique = [];
            foreach ($merged as $ch) {
                $unique[$ch['id']] = $ch;
            }
            $channel->assigned_channels = json_encode(array_values($unique));
        }
        // if (isset($this->request->assigned_channels)) {
        //     $channel->assigned_channels = json_encode($this->request->assigned_channels);
        // }
        if (isset($this->request->is_active)) {
            $channel->is_active = !empty($this->request->is_active) ? 1 : 0;
        }
        $channel->by_user = Auth::user()->id;
        if ($channel->save()) {
            return 'success';
        } else {
            return 'error';
        }
    }


    public function prepareGrid()
    {
        $this->setGridModel($this->_liveset)
            ->setEagerLoadingModels(['getorg', 'getuser']);
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