<?php

namespace Contus\Drm\Repositories;

use Contus\Base\Repository;
use Contus\Drm\Model\Drm;
use Contus\Drm\Model\DrmDetails;
use Contus\Base\Helpers\StringLiterals;
use Illuminate\Support\Facades\Log;

class DrmRepository extends Repository
{

    protected $_drm;
    protected $_drmdetails;

    public function __construct(Drm $drm, DrmDetails $drmDetails)
    {
        parent::__construct();
        $this->_drm = $drm;
        $this->_drmdetails = $drmDetails;
    }

    // public function drm_info(){
    //     return DrmDetails::all();
    // }

    public function adddrm()
    {
        $this->setRules([
            'drm_name' => 'required',
        ]);
        $this->_validate();

        $drm = Drm::create([
            'drm_name' => $this->request->input('drm_name'),
        ]);

        // Log::info('DRM created:', $drm->toArray());

        $drmDetails = DrmDetails::create([
            'drm_id' => $drm->id,
            'drm_name' => $drm->drm_name,
        ]);

        // Log::info('DRM Details created:', $drmDetails->toArray());

        return response()->json([
            'success' => true,
            'message' => trans('drm::index.add.success'),
        ]);
    }

    public function prepareGrid()
    {
        // Log::info('INFO ', [$this->_drmdetails]);
        $this->setGridModel($this->_drmdetails)->setEagerLoadingModels(['drmprofile']);
        return $this;
    }


    protected function searchFilter($builderCoupon)
    {
        $searchRecordUsers = $this->request->has(StringLiterals::SEARCHRECORD) && is_array($this->request->input(StringLiterals::SEARCHRECORD)) ? $this->request->input(StringLiterals::SEARCHRECORD) : [];
        foreach ($searchRecordUsers as $key => $value) {
            if ($key == 'publish_now' && $value == 'all') {
                continue;
            }

            if ($key == 'valid_till') {
                $date = date_create($value);
                $value = date_format($date, "Y-m-d");
            }
            $builderCoupon = $builderCoupon->where($key, 'like', "%$value%");
        }
        return $builderCoupon;
    }

    public function getGridHeadings()
    {
        return [
            'heading' => [
                ['name' => 'S.No', 'S.No' => 'SNo', 'sort' => false],
                ['name' => trans('drm::index.drm_name'), 'value' => '', 'sort' => true],
                ['name' => trans('drm::index.drm_provider'), 'value' => '', 'sort' => false],
                ['name' => trans('drm::index.drm_profile'), 'value' => '', 'sort' => false],
                ['name' => trans('drm::index.status'), 'value' => '', 'sort' => false],
                ['name' => trans('drm::index.action'), 'value' => '', 'sort' => false],
            ]
        ];
    }
}
