<?php

namespace Contus\Organizations\Repositories;

use Contus\Base\Helpers\StringLiterals;
use Contus\Base\Repository;
use Contus\Organizations\Model\ChannelContet;
use Contus\Organizations\Model\OrganizationContentSets;
use Contus\Organizations\Model\OrganizationMonitizationPlan;
use Illuminate\Support\Facades\Auth;

class ContetntSetRepository extends Repository
{
    protected $_channelContet;

    public function __construct(ChannelContet $channelContet)
    {
        parent::__construct();
        $this->_channelContet = $channelContet;
    }

    public function Channelset()
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

        $channelContentSet = new ChannelContet();

        if (isset($requestData['cover_image']) && $requestData['cover_image'] != '') {
            $thumbUrl = explode("/", $requestData['cover_image']);
            $fileName = $channelContentSet->getImageBaseName($thumbUrl[count($thumbUrl) - 1]);
            // $folderName = config("contus.base.image.posters.s3_location");
            $localStoragePath = config("app.url") . config("contus.base.image.posters.temporary_image_storage_path");
            // $s3BucketImgFilename = $this->uploadTos3Bucket($fileName, $folderName, $localStoragePath);
            // $s3BucketImgURL = $folderName . $s3BucketImgFilename;
            $localIamgePath = $localStoragePath . DIRECTORY_SEPARATOR . $fileName;
            $channelContentSet->cover_image = $localIamgePath;
            // Log::info('Processed poster image.', ['poster_path' => $localIamgePath]);;
        }

        $channelContentSet->organization_id = $requestData['organization_id'];
        $channelContentSet->name = $requestData['name'];
        $channelContentSet->description = $requestData['description'];
        $channelContentSet->monitization_type = $requestData['monitization_type'];
        $channelContentSet->payment_method = $requestData['payment_method'];
        $channelContentSet->price = $requestData['price'];
        $channelContentSet->currency = $requestData['currency'];
        $channelContentSet->assigned_channels = json_encode($requestData['assigned_channels']);
        $channelContentSet->by_user = $user->id;
        $channelContentSet->is_active = !empty($requestData['is_active']) ? 1 : 0;

        if ($channelContentSet->save()) {
            return 'success';
        } else {
            return 'error';
        }
    }

    public function postEdit($id)
    {
        $channel = $this->_channelContet->find($id);

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
            $existingChannels = json_decode($channel->assigned_channels, true) ?? [];
            $newChannels = json_decode(json_encode($this->request->assigned_channels), true);
            $merged = array_merge($existingChannels, $newChannels);
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
        if (isset($this->request->period)) {
            $channel->period = $this->request->period;
        }
        if (isset($this->request->period_type)) {
            $channel->period_type = $this->request->period_type;
        }
        if (isset($this->request->rent_price)) {
            $channel->rent_price = $this->request->rent_price;
        }
        if (isset($this->request->rent_payment_method)) {
            $channel->rent_payment_method = $this->request->rent_payment_method;
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
        $this->setGridModel($this->_channelContet)
            ->setEagerLoadingModels(['getorg', 'getuser']);
        return $this;
    }

    public function getGridHeadings()
    {
        return [
            'heading' => [
                ['name' => 'S.No', 'value' => 'SNo', 'sort' => false],
                ['name' => 'Set Name', 'value' => '', 'sort' => false],
                ['name' => 'Channel Quantity', 'value' => '', 'sort' => false],
                ['name' => 'Listed', 'value' => '', 'sort' => false],
                ['name' => 'Monetization Type', 'value' => '', 'sort' => false],
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
