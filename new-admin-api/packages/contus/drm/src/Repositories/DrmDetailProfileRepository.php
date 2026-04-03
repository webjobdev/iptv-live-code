<?php

namespace Contus\Drm\Repositories;

use Contus\Base\Repository;
use Firebase\JWT\JWT;
use Contus\Drm\Model\Drm;
use Contus\Drm\Model\DrmDetails;
use Contus\Drm\Model\DrmProfileDetails;
use Illuminate\Support\Facades\Log;

class DrmDetailProfileRepository extends Repository {
    protected $_drmprofiledetails;

    public function __construct(DrmProfileDetails $drmProfileDetails) {
        parent::__construct();
        $this->_drmprofiledetails = $drmProfileDetails;
    }

    public function addprodrm() {
        $drmId = $this->request->input('drm_id', $this->request->input('id'));
        // Log::info('[DRM] Creating or updating DRM profile detail.', ['drm_id' => $drmId]);

        // $validater = $this->request->validate([
        //     'is_active'                     => 'required|in:0,1',
        //     'drm_provider'                  => 'nullable|string|max:255',
        //     'drm_type'                      => 'nullable|string|max:255',
        //     'authorization_url'             => 'required|string|max:255',
        //     'license_persistent'            => 'required|string|max:255',
        //     'license_limitation'            => 'required|string|max:255',
        //     'license_duration'              => 'required|string|max:255',
        //     'hdcp_type'                     => 'required|string|max:255',
        //     'robustness'                    => 'required|string|max:255',
        //     'output_protection_level'       => 'required|string|max:255',
        //     'integration_type'              => 'required|string|max:255',
        //     'playready_security_level'      => 'required|string|max:255',
        //     'hardware_drm_required'         => 'required|string|max:255',
        //     'rooted_devices_allowed'        => 'required|string|max:255',
        //     'fps_certificate'               => 'required|image|mimes:der,cer,pdf,gif|max:2048',
        // ]);

        // $this->_validate();

        $fields = [
            'drm_provider',
            'drm_type',
            'authorization_url',
            'license_persistent',
            'license_limitation',
            'license_duration',
            'hdcp_type',
            'robustness',
            'output_protection_level',
            'integration_type',
            'playready_security_level',
            'hardware_drm_required',
            'rooted_devices_allowed',
        ];

        $data = [];

        foreach ($fields as $field) {
            // dd($field);
            $value = $this->request->input($field);
            if (
                $this->request->has($field) &&
                $value !== null &&
                $value !== '' &&
                $value !== 'undefined'
            ) {
                $data[$field] = $value;
            }
        }

        // Log::info('[DRM] Collected input fields:', $data);

        // Handle is_active explicitly
        if ($this->request->has('is_active')) {
            $data['is_active'] = $this->request->input('is_active') ? 1 : 0;
            // Log::info('[DRM] is_active received and set to:', ['is_active' => $data['is_active']]);
        } else {
            // Log::info('[DRM] is_active field was not present in request.');
        }

        // Handle file upload if present
        if ($this->request->hasFile('fps_certificate')) {
            $file = $this->request->file('fps_certificate');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/certificate'), $filename);
            $data['fps_certificate'] = 'uploads/certificate/' . $filename;
            // Log::info('[DRM] Certificate uploaded.', ['filename' => $filename]);
        }

        // Log the final payload before saving
        // Log::info('[DRM] Final payload for updateOrCreate:', $data);

        // 🔐 Generate EZDRM Authorization URL if provider is EZDRM
        if ($this->request->input('drm_provider') === 'EZDRM') {
            // Log::info('[DRM] EZDRM selected as provider.');

            $drmType = $this->request->input('drm_type');
            $drmDetails = DrmDetails::find($drmId);
            $px = optional($drmDetails)->px_value;

            // Log::info('[DRM] Retrieved pX value from drm_details.', [
            //     'drm_id' => $drmId,
            //     'px_value' => $px,
            //     'drm_type' => $drmType
            // ]);

            if ($px) {
                $customData = 'video_' . $drmId;

                switch ($drmType) {
                    case 'Widevine':
                        $authorizationUrl = "https://widevine-dash.ezdrm.com/proxy?pX={$px}&customdata={$customData}";
                        break;

                    case 'FairPlay':
                        // $spc = 'INSERT_SPC_HERE';
                        $assetId = 'video_' . $drmId;
                        // $authorizationUrl = "https://fp-license.ezdrm.com/fp?spc={$spc}&assetId={$assetId}";
                        $authorizationUrl = "https://fp-license.ezdrm.com/fp?assetId={$assetId}";

                        break;

                    case 'PlayReady':
                        $authorizationUrl = "https://playready.ezdrm.com/cency/preauth.aspx?pX={$px}&customdata={$customData}";
                        break;

                    default:
                        // Log::warning('[DRM] Unsupported drm_type for EZDRM.', ['drm_type' => $drmType]);
                        $authorizationUrl = null;
                }

                if ($authorizationUrl) {
                    $data['authorization_url'] = $authorizationUrl;
                    // Log::info('[DRM] Authorization URL generated.', [
                    //     'authorization_url' => $authorizationUrl,
                    //     'customData' => $customData
                    // ]);
                }
            } else {
                // Log::warning('[DRM] No pX found in drm_details table. Cannot generate authorization URL.', ['drm_id' => $drmId]);
            }
        }


        try {
            $drmDetail = DrmProfileDetails::updateOrCreate(
                ['drm_details_id' => $drmId],
                $data
            );

            // Log::info('[DRM] Record saved successfully.', ['drm_details_id' => $drmId]);

            // If this is a new record, ensure foreign key is set
            if (!$drmDetail->drm_details_id) {
                $drmDetail->drm_details_id = $drmId;
                $drmDetail->save();
                // Log::info('[DRM] Foreign key set after creation.', ['drm_details_id' => $drmId]);
            }
        } catch (\Exception $e) {
            // Log::error('[DRM] Failed to save DRM profile detail.', [
            //     'drm_details_id' => $drmId,
            //     'error' => $e->getMessage(),
            // ]);
        }

        // Log::info('[DRM] DRM profile created or updated successfully.', ['record_id' => $drmDetail->id]);

        return response()->json([
            'success' => true,
            'message' => trans('drm::index.update.success'),
        ]);
    }

    public function prepareGrid() {
        // dd($this->_drmdetails); 

        $this->setGridModel($this->_drmprofiledetails)->setEagerLoadingModels(['drmprofile']);
        return $this;
    }
}
