<?php

namespace Contus\Drm\Repositories;

use Contus\Base\Repository;
use Contus\Drm\Model\Drm;
use Contus\Drm\Model\DrmDetails;
use Contus\Drm\Model\DrmProfileDetails;
use Illuminate\Support\Facades\Log;

class DrmDetailAddRepository extends Repository
{
    protected $_drmdetails;

    public function __construct(DrmDetails $drmDetails)
    {
        parent::__construct();
        $this->_drmdetails = $drmDetails;
    }

    public function addaccdrm()
    {
        $drmId = $this->request->input('drm_id', $this->request->input('id'));
        // Log::info('Starting general organization settings update.', ['drm_id' => $drmId]);

        $drm = Drm::find($drmId);
        // if (!$drm) {
        //     Log::warning('Drm not found.', ['drm_id' => $drmId]);
        // }

        $drmDetail = DrmDetails::firstOrNew(['drm_id' => $drmId]);

        $this->setRules([
            'drm_name' => 'required|string|max:255',
            'drm_provider' => 'required|string|max:255',
            'px_value' => 'nullable|string|max:255',
            'account_id' => 'nullable|string|max:255',
            'site_key' => 'nullable|string|max:255',
            'access_key' => 'nullable|string|max:255',
            'publish_now' => 'nullable|boolean',
        ]);

        $this->_validate();

        $fields = ['drm_name', 'drm_provider', 'px_value', 'account_id', 'site_key', 'access_key'];

        foreach ($fields as $field) {
            if (
                $this->request->has($field) &&
                $this->request->input($field) !== null &&
                $this->request->input($field) !== '' &&
                $this->request->input($field) !== 'undefined'
            ) {
                $drmDetail->$field = $this->request->input($field);
            }
            if ($field === 'drm_name' && $drm) {
                $drm->drm_name = $this->request->input($field);
            }
        }

        // Special handling for publish_now (set to 1 only if present and truthy)
        if ($this->request->has('publish_now')) {
            $drmDetail->publish_now = $this->request->input('publish_now') ? 1 : 0;
        }

        $drmDetail->save();

        if ($drm) {
            $drm->save();
        }

        // Log::info('Drm detail updated:', $drmDetail->toArray());

        $drmprofile = DrmProfileDetails::updateOrCreate(
            ['drm_details_id' => $drmDetail->drm_id],
            ['drm_provider' => $drmDetail->drm_provider]
        );

        // Log::info('Drm detail insert into drm profile detail:', $drmprofile->toArray());

        return response()->json([
            'success' => true,
            'message' => trans('drm::index.update.success'),
        ]);
    }

    public function prepareGrid()
    {
        $this->setGridModel($this->_drmdetails)->setEagerLoadingModels(['drmprofile']);
        return $this;
    }
}
