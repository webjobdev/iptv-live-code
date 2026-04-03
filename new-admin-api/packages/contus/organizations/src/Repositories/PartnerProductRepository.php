<?php

namespace Contus\Organizations\Repositories;

use Contus\Base\Helpers\StringLiterals;
use Contus\Base\Repository;
use Contus\Organizations\Model\PartnerProduct;
use Google\Service\CloudSourceRepositories\Repo;
use Illuminate\Support\Facades\Auth;

class PartnerProductRepository extends Repository {
    protected $_partnerProduct;
    public function __construct(PartnerProduct $partnerProduct) {
        parent::__construct();
        $this->_partnerProduct = $partnerProduct;
    }

    public function postAdd() {
        return $this->postCreate($this->request->all());
    }

    public function postCreate($requestData) {
        $user = Auth::user();

        $this->setRules([
            'product_name' => 'required',
            'product_description' => 'required',
            'product_id' => 'required',
            'partner_program' => 'required',
            'organization_id' => 'required',
        ]);

        $this->_validate();

        $ppdata = new PartnerProduct();

        if (isset($requestData['product_image']) && $requestData['product_image'] != '') {
            $thumbUrl = explode("/", $requestData['product_image']);
            $fileName = $ppdata->getImageBaseName($thumbUrl[count($thumbUrl) - 1]);
            // $folderName = config("contus.base.image.thumbnail.s3_location");
            $localStoragePath = config("app.url") . config("contus.base.image.thumbnail.temporary_image_storage_path");
            // $s3BucketImgFilename = $this->uploadTos3Bucket($fileName, $folderName, $localStoragePath);
            // $s3BucketImgURL = $folderName . $s3BucketImgFilename;
            $localIamgePath = $localStoragePath . DIRECTORY_SEPARATOR . $fileName;
            $ppdata->product_image = $localIamgePath;
            // Log::info('Processed thumbnail image.', ['thumbnail_path' => $localIamgePath]);
        }

        $ppdata->product_name = $requestData['product_name'];
        $ppdata->product_description = $requestData['product_description'];
        $ppdata->product_id = $requestData['product_id'];
        $ppdata->partner_program = $requestData['partner_program'];
        $ppdata->organization_id = $requestData['organization_id'];
        $ppdata->by_user = $user->id;

        if ($ppdata->save()) {
            return 'success';
        } else {
            return 'error';
        }
    }


    public function postEdit($id) {
        if (!empty($id)) {
            $data = $this->_partnerProduct->find($id);
            $user = Auth::user();

            $this->setRules([
                'product_name' => 'required',
                'product_description' => 'required',
                'product_id' => 'required',
                // 'partner_program' => 'required',
                'organization_id' => 'required',
            ]);

            $this->validate($this->request, $this->getRules());

            if (isset($this->request->product_image)) {
                $thumbUrl = explode("/", $this->request->product_image);
                $fileName = $data->getImageBaseName($thumbUrl[count($thumbUrl) - 1]);
                // $folderName = config("contus.base.image.thumbnail.s3_location");
                $localStoragePath = config("app.url") . config("contus.base.image.thumbnail.temporary_image_storage_path");
                // $s3BucketImgFilename = $this->uploadTos3Bucket($fileName, $folderName, $localStoragePath);
                // $s3BucketImgURL = $folderName . $s3BucketImgFilename;
                $localIamgePath = $localStoragePath . DIRECTORY_SEPARATOR . $fileName;
                $data->product_image = $localIamgePath;
                // Log::info('Processed thumbnail image.', ['thumbnail_path' => $localIamgePath]);
            }

            $data->product_name = $this->request->product_name;
            $data->product_description = $this->request->product_description;
            $data->product_id = $this->request->product_id;
            $data->partner_program = $this->request->partner_program ?? $data->partner_program;
            $data->organization_id = $this->request->organization_id;
            $data->by_user = $user->id;

            if ($data->save()) {
                return true;
            }
        } else {
            return 'false';
        }
    }

    public function prepareGrid() {
        $this->setGridModel($this->_partnerProduct)
            ->setEagerLoadingModels(['ByUser']);
        return $this;
    }

    public function getGridHeadings() {
        return [
            'heading' => [
                ['name' => 'S.No', 'value' => 'SNo', 'sort' => false],
                ['name' => 'Product Name', 'value' => '', 'sort' => true],
                ['name' => 'Program Name', 'value' => '', 'sort' => false],
                ['name' => 'By User', 'value' => '', 'sort' => false],
                ['name' => 'Updated', 'value' => '', 'sort' => false],
                ['name' => 'Actions', 'value' => '', 'sort' => false],
            ]
        ];
    }

    protected function searchFilter($builderCoupon) {
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
