<?php

namespace Contus\Organizations\Repositories\AppCustomization;

use Contus\Base\Repository;
use Contus\Organizations\Model\ChannelContet;
use Contus\Organizations\Model\LiveEventContent;
use Contus\Organizations\Model\RowOrder;
use Contus\Organizations\Model\TvShowContent;
use Contus\Organizations\Model\VodContent;
use Illuminate\Http\Request;

class RowOrderRepository extends Repository
{

    protected $_acro;

    public function __construct(RowOrder $rowOrder)
    {
        parent::__construct();
        $this->_acro = $rowOrder;
    }


    public function postAdd()
    {
        return $this->postCreate($this->request->all());
    }

    public function postCreate($requestData)
    {
        $this->setRules([
            'organization_id' => 'nullable',
            'title' => 'required',
            'description' => 'nullable',
            'assigne_row' => 'array|nullable',
            'poster_type' => 'nullable',
            'poster_size' => 'nullable',
            'gradient' => 'nullable',
            // 'no_set' => 'nullable'
        ]);

        $this->_validate();

        $insert = new RowOrder();

        if (isset($requestData['vertical_image']) && $requestData['vertical_image'] != '') {
            $thumbUrl = explode("/", $requestData['vertical_image']);
            $fileName = $insert->getImageBaseName($thumbUrl[count($thumbUrl) - 1]);
            // $folderName = config("contus.base.image.thumbnail.s3_location");
            $localStoragePath = config("app.url") . config("contus.base.image.thumbnail.temporary_image_storage_path");
            // $s3BucketImgFilename = $this->uploadTos3Bucket($fileName, $folderName, $localStoragePath);
            // $s3BucketImgURL = $folderName . $s3BucketImgFilename;
            $localIamgePath = $localStoragePath . DIRECTORY_SEPARATOR . $fileName;
            $insert->vertical_image = $localIamgePath;
            // Log::info('Processed thumbnail image.', ['thumbnail_path' => $localIamgePath]);
        }
        if (isset($requestData['horizontal_image']) && $requestData['horizontal_image'] != '') {
            $thumbUrl = explode("/", $requestData['horizontal_image']);
            $fileName = $insert->getImageBaseName($thumbUrl[count($thumbUrl) - 1]);
            // $folderName = config("contus.base.image.posters.s3_location");
            $localStoragePath = config("app.url") . config("contus.base.image.posters.temporary_image_storage_path");
            // $s3BucketImgFilename = $this->uploadTos3Bucket($fileName, $folderName, $localStoragePath);
            // $s3BucketImgURL = $folderName . $s3BucketImgFilename;
            $localIamgePath = $localStoragePath . DIRECTORY_SEPARATOR . $fileName;
            $insert->horizontal_image = $localIamgePath;
            // Log::info('Processed poster image.', ['poster_path' => $localIamgePath]);;
        }

        $insert->organization_id = $requestData['organization_id'];
        $insert->title = $requestData['title'];
        $insert->description = $requestData['description'];
        $insert->assigne_row = $requestData['assigne_row'];
        $insert->poster_type = $requestData['poster_type'];
        $insert->poster_size = $requestData['poster_size'];
        $insert->gradient = $requestData['gradient'];
        // $insert->no_set = $requestData['no_set'];

        if ($insert->save()) {
            return 'success';
        } else {
            return 'false';
        }
    }

    public function saveOrder()
    {
        foreach (request()->rows as $row) {
            RowOrder::where('id', $row['id'])
                ->update(['row_order' => $row['order']]);
        }

        return 'success';

        // return response()->json([
        //     "success" => true,
        //     "message" => "Order updated"
        // ], 200);
    }

    public function prepareGrid()
    {
        $this->setGridModel($this->_acro);
        return $this;
    }
}
