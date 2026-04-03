<?php

namespace Contus\Organizations\Repositories\AppCustomization;

use Contus\Base\Repository;
use Contus\Organizations\Model\OrgMonetizationPlanss;
use Illuminate\Support\Facades\Log;

class AppCustomiztionBannerCarouselRepository extends Repository
{
    protected $_OrgMonetizationPlanss;

    public function __construct(OrgMonetizationPlanss $OrgMonetizationPlanss)
    {
        parent::__construct();
        $this->_OrgMonetizationPlanss = $OrgMonetizationPlanss;
    }

    public function postEdit($id)
    {
        $Edit = $this->_OrgMonetizationPlanss->find($id);
        // dd($plan);

        // if (isset($requestData['banners']) && $Edit['banners'] != '') {
        //     $thumbUrl = explode("/", $Edit['banners']);
        //     $fileName = $Edit->getImageBaseName($thumbUrl[count($thumbUrl) - 1]);
        //     // $folderName = config("contus.base.image.thumbnail.s3_location");
        //     $localStoragePath = config("app.url") . config("contus.base.image.thumbnail.temporary_image_storage_path");
        //     // $s3BucketImgFilename = $this->uploadTos3Bucket($fileName, $folderName, $localStoragePath);
        //     // $s3BucketImgURL = $folderName . $s3BucketImgFilename;
        //     $localIamgePath = $localStoragePath . DIRECTORY_SEPARATOR . $fileName;
        //     $Edit->banners = $localIamgePath;
        //     // Log::info('Processed thumbnail image.', ['thumbnail_path' => $localIamgePath]);
        // }

        $Edit->auto_scrolling = $this->request->input('auto_scrolling') ?? null;
        $Edit->second = $this->request->input('second') ?? null;
        $Edit->subscription_name = $this->request->input('subscription_name') ?? null;
        $Edit->banners = $this->request->input('banners');

        $Edit->save();

        return true;
    }

    public function postToggle($id)
    {
        if (!empty($id)) {
            $banner = $this->_OrgMonetizationPlanss->find($id);

            $banner->banner_carousel_is_active = $this->request->banner_carousel_is_active ? 1 : 0;
            $banner->save();

            return response()->json([
                'success' => true,
                'message' => 'Banner Data Update Successfully.',
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Invalid ID'], 400);
    }


    public function postDelete($id)
    {
        if (empty($id)) {
            return response()->json(['message' => 'Invalid ID.'], 400);
        }

        $plans = $this->_OrgMonetizationPlanss->whereNotNull('banners')->get();

        foreach ($plans as $plan) {
            // normalize banners to array
            $banners = $plan->banners;
            if (is_string($banners)) {
                $banners = json_decode($banners, true);
            }

            if (empty($banners) || !is_array($banners)) {
                continue;
            }

            // remove matching banner using classic closure
            $updated = array_filter($banners, function ($b) use ($id) {
                return (string) ($b['id'] ?? '') !== (string) $id;
            });

            if (count($updated) !== count($banners)) {
                $plan->banners = array_values($updated); // reindex
                $plan->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Banner deleted successfully.',
                    'plan_id' => $plan->id,
                ]);
            }
        }

        return response()->json(['message' => 'Banner ID not found.'], 404);
    }





}