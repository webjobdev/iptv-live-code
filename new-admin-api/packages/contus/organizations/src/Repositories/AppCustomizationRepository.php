<?php

namespace Contus\Organizations\Repositories;

use Contus\Base\Helpers\StringLiterals;
use Contus\Base\Repository;
use Contus\Organizations\Model\AppCustomiztionGeneral;
use Contus\Organizations\Model\OrganizationAppCustomization;
use Illuminate\Support\Facades\Log;

class AppCustomizationRepository extends Repository
{
    protected $customization;

    public function __construct(AppCustomiztionGeneral $organizationAppCustomization)
    {
        parent::__construct();
        $this->customization = $organizationAppCustomization;
    }

    public function postAdd()
    {
        return $this->postCreate($this->request->all());
    }

    public function postCreate($requestData)
    {

        $this->setRules([
            'organization_id' => 'nullable',
            'live' => 'nullable|in:0,1',
            'epg' => 'nullable|in:0,1',
            'catchup' => 'nullable|in:0,1',
            'movie' => 'nullable|in:0,1',
            'sereis' => 'nullable|in:0,1',
            'event' => 'nullable|in:0,1',
        ]);

        $this->_validate();

        $insert = new AppCustomiztionGeneral();

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

        $insert->organization_id = $requestData['organization_id'];
        $insert->live = isset($requestData['live']) ? 1 : 0;
        $insert->epg = isset($requestData['epg']) ? 1 : 0;
        $insert->catchup = isset($requestData['catchup']) ? 1 : 0;
        $insert->movie = isset($requestData['movie']) ? 1 : 0;
        $insert->sereis = isset($requestData['sereis']) ? 1 : 0;
        $insert->event = isset($requestData['event']) ? 1 : 0;

        $insert->save();

        return 'success';
    }

    public function postEdit($id)
    {
        if (!empty($id)) {
            $data = $this->customization->find($id);

            $this->setRules([
                'organization_id' => 'nullable',
            ]);

            $this->validate($this->request, $this->getRules());

            if (!empty($this->request->thumbnail_image)) {
                $thumbUrl = explode("/", $this->request->thumbnail_image);
                $fileName = $data->getImageBaseName($thumbUrl[count($thumbUrl) - 1]);
                // $folderName = config("contus.base.image.thumbnail.s3_location");
                $localStoragePath = config("app.url") . config("contus.base.image.thumbnail.temporary_image_storage_path");
                // $s3BucketImgFilename = $this->uploadTos3Bucket($fileName, $folderName, $localStoragePath);
                // $s3BucketImgURL = $folderName . $s3BucketImgFilename;
                $localIamgePath = $localStoragePath . DIRECTORY_SEPARATOR . $fileName;
                $data->thumbnail_image = $localIamgePath;
                // Log::info('Processed thumbnail image.', ['thumbnail_path' => $localIamgePath]);
            }

            $data->organization_id = $this->request->organization_id;
            $data->live = $this->request->live ? 1 : 0;
            $data->epg = $this->request->epg ? 1 : 0;
            $data->catchup = $this->request->catchup ? 1 : 0;
            $data->movie = $this->request->movie ? 1 : 0;
            $data->sereis = $this->request->sereis ? 1 : 0;
            $data->event = $this->request->event ? 1 : 0;

            $data->save();
            return
                true;
        } else {
            return
                false;
        }

    }

    public function prepareGrid()
    {
        $this->setGridModel($this->customization);
        return $this;
    }

    public function getGridHeadings()
    {
        return [
            'heading' => [
                ['name' => 'S.No', 'value' => 'SNo', 'sort' => false],
                ['name' => 'Logo', 'value' => '', 'sort' => true],
                ['name' => 'Live', 'value' => '', 'sort' => false],
                ['name' => 'Epg', 'value' => '', 'sort' => false],
                ['name' => 'Catchup', 'value' => '', 'sort' => false],
                ['name' => 'Movie', 'value' => '', 'sort' => false],
                ['name' => 'Sereis', 'value' => '', 'sort' => false],
                ['name' => 'Event', 'value' => '', 'sort' => false],
                ['name' => 'Action', 'value' => '', 'sort' => false],
            ]
        ];
    }

    protected function searchFilter($builderCoupon)
    {
        $searchRecordUsers = $this->request->has(StringLiterals::SEARCHRECORD) &&
            is_array($this->request->input(StringLiterals::SEARCHRECORD)) ?
            $this->request->input(StringLiterals::SEARCHRECORD) : [];

        foreach ($searchRecordUsers as $key => $value) {
            if ($key == 'is_active' && $value == 'all') {
                continue;
            }

            $builderCoupon = $builderCoupon->where($key, 'like', "%$value%");
        }
        return $builderCoupon;

    }
}
