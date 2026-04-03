<?php

namespace Contus\Organizations\Repositories\AppCustomization;

use Contus\Base\Helpers\StringLiterals;
use Contus\Base\Repository;
use Contus\Organizations\Model\BannerCarouselsSubscription;

class BannerCarouselsSubscriptionRepository extends Repository
{

    protected $_bannercrss;

    public function __construct(BannerCarouselsSubscription $bannercs)
    {
        parent::__construct();
        $this->_bannercrss = $bannercs;
    }

    public function postAdd()
    {
        return $this->postCreate($this->request->all());
    }

    public function postCreate($requestData)
    {
        $this->setRules([
            'organization_id' => 'nullable|required',
            'resource_type' => 'nullable',
            'select_platform' => 'array|required',
            'name' => 'required',
            'content_type' => 'nullable',
            'target_link' => 'nullable',
            'is_active' => 'nullable',
        ]);

        $this->_validate();

        $insert = new BannerCarouselsSubscription();

        if (isset($requestData['thumbnail_image']) && $requestData['thumbnail_image'] != '') {
            $thumbUrl = explode("/", $requestData['thumbnail_image']);
            $fileName = $insert->getImageBaseName($thumbUrl[count($thumbUrl) - 1]);
            // $folderName = config("contus.base.image.thumbnail.s3_location");
            $localStoragePath = config("app.url") . config("contus.base.image.thumbnail.temporary_image_storage_path");
            // $s3BucketImgFilename = $this->uploadTos3Bucket($fileName, $folderName, $localStoragePath);
            // $s3BucketImgURL = $folderName . $s3BucketImgFilename;
            $localIamgePath = $localStoragePath . DIRECTORY_SEPARATOR . $fileName;
            $insert->thumbnail_image = $localIamgePath;
            // Log::info('Processed thumbnail image.', ['thumbnail_path' => $localIamgePath]);
        }
        if (isset($requestData['poster_image']) && $requestData['poster_image'] != '') {
            $thumbUrl = explode("/", $requestData['poster_image']);
            $fileName = $insert->getImageBaseName($thumbUrl[count($thumbUrl) - 1]);
            // $folderName = config("contus.base.image.posters.s3_location");
            $localStoragePath = config("app.url") . config("contus.base.image.posters.temporary_image_storage_path");
            // $s3BucketImgFilename = $this->uploadTos3Bucket($fileName, $folderName, $localStoragePath);
            // $s3BucketImgURL = $folderName . $s3BucketImgFilename;
            $localIamgePath = $localStoragePath . DIRECTORY_SEPARATOR . $fileName;
            $insert->poster_image = $localIamgePath;
            // Log::info('Processed poster image.', ['poster_path' => $localIamgePath]);;
        }

        $insert->banner_id = $requestData['banner_id'];
        $insert->plan_id = $requestData['plan_id'];
        $insert->organization_id = $requestData['organization_id'];
        $insert->resource_type = $requestData['resource_type'];
        $insert->name = $requestData['name'];
        $insert->content_type = $requestData['content_type'] ?? null;
        $insert->target_link = $requestData['target_link'] ?? null;
        $insert->select_platform = $requestData['select_platform'];
        $insert->is_active = !empty($requestData['is_active']) ? 1 : 0;

        $insert->save();

        return 'success';
    }

    public function postEdit($id)
    {
        if (!empty($id)) {
            $edit = $this->_bannercrss->find($id);

            $this->setRules([
                'resource_type' => 'required',
                'select_platform' => 'array|required',
            ]);

            $this->validate($this->request, $this->getRules());

            if (isset($this->request->thumbnail_image) && $this->request->thumbnail_image != '') {
                $thumbUrl = explode("/", $this->request->thumbnail_image);
                $fileName = $edit->getImageBaseName($thumbUrl[count($thumbUrl) - 1]);
                // $folderName = config("contus.base.image.thumbnail.s3_location");
                $localStoragePath = config("app.url") . config("contus.base.image.thumbnail.temporary_image_storage_path");
                // $s3BucketImgFilename = $this->uploadTos3Bucket($fileName, $folderName, $localStoragePath);
                // $s3BucketImgURL = $folderName . $s3BucketImgFilename;
                $localIamgePath = $localStoragePath . DIRECTORY_SEPARATOR . $fileName;
                $edit->thumbnail_image = $localIamgePath;
                // Log::info('Processed thumbnail image.', ['thumbnail_path' => $localIamgePath]);
            }
            if (isset($this->request->poster_image) && $this->request->poster_image != '') {
                $thumbUrl = explode("/", $this->request->poster_image);
                $fileName = $edit->getImageBaseName($thumbUrl[count($thumbUrl) - 1]);
                // $folderName = config("contus.base.image.posters.s3_location");
                $localStoragePath = config("app.url") . config("contus.base.image.posters.temporary_image_storage_path");
                // $s3BucketImgFilename = $this->uploadTos3Bucket($fileName, $folderName, $localStoragePath);
                // $s3BucketImgURL = $folderName . $s3BucketImgFilename;
                $localIamgePath = $localStoragePath . DIRECTORY_SEPARATOR . $fileName;
                $edit->poster_image = $localIamgePath;
                // Log::info('Processed poster image.', ['poster_path' => $localIamgePath]);;
            }

            $edit->banner_id = $this->request->banner_id;
            $edit->plan_id = $this->request->plan_id;
            $edit->organization_id = $this->request->organization_id;
            $edit->resource_type = $this->request->resource_type;
            $edit->name = $this->request->name;
            $edit->content_type = $this->request->content_type ?? null;
            $edit->target_link = $this->request->target_link ?? null;
            $edit->select_platform = json_encode($this->request->select_platform);
            $edit->is_active = !empty($this->request->is_active) ? 1 : 0;

            $edit->save();

            return 'true';

        } else {
            return
                'false';
        }
    }


    public function prepareGrid()
    {
        $this->setGridModel($this->_bannercrss);
        return $this;
    }

    public function fetchRecord()
    {
        $data = BannerCarouselsSubscription::paginate(15);
        return $data;
    }

    public function getGridHeadings()
    {
        return [
            'heading' => [
                ['name' => 'S.No', 'value' => 'SNo', 'sort' => false],
                ['name' => 'Resouce Type', 'value' => '', 'sort' => true],
                ['name' => 'Select Platform', 'value' => '', 'sort' => false],
                ['name' => 'Content Type', 'value' => '', 'sort' => false],
                ['name' => 'Target Link', 'value' => '', 'sort' => true],
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

    }
}