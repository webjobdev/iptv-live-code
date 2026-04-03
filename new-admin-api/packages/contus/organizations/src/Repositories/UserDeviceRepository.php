<?php

namespace Contus\Organizations\Repositories;

use Contus\Base\Helpers\StringLiterals;
use Contus\Base\Repository;
use Contus\Organizations\Model\OrgDevices;

class UserDeviceRepository extends Repository {
    protected $_devices;

    public function __construct(OrgDevices $orgDevices) {
        parent::__construct();
        $this->_devices = $orgDevices;
    }


    public function prepareGrid() {
        $this->setGridModel($this->_devices);
        return $this;
    }

    public function getGridHeadings() {
        return ['heading' => [
            ['name' => 'S.No', 'S.No' => 'SNo', 'sort' => false],
            ['name' => trans('organizations::index.brand_model'), 'value' => '', 'sort' => true],
            ['name' => trans('organizations::index.mac_address'), 'value' => '', 'sort' => false],
            ['name' => trans('organizations::index.serial_number'), 'value' => '', 'sort' => false],
            ['name' => trans('organizations::index.identifier'), 'value' => '', 'sort' => false],
            ['name' => trans('organizations::index.ip_address'), 'value' => '', 'sort' => false],
            ['name' => trans('organizations::index.location'), 'value' => '', 'sort' => false],
            ['name' => trans('organizations::index.last_session'), 'value' => '', 'sort' => false],
            ['name' => trans('organizations::index.status'), 'value' => '', 'sort' => false],
            ['name' => trans('organizations::index.action'), 'value' => '', 'sort' => false],
        ]];
    }

    protected function searchFilter($builder) {
        $searchRecord = $this->request->has(StringLiterals::SEARCHRECORD) && is_array($this->request->input(StringLiterals::SEARCHRECORD))
            ? $this->request->input(StringLiterals::SEARCHRECORD)
            : [];

        foreach ($searchRecord as $key => $value) {
            if ($value === null || $value === '' || $value === 'all') {
                continue;
            }

            if ($key === 'is_active' || $key === 'status') {
                $builder = $builder->where('status', (int) $value);
            } else {
                $builder = $builder->where($key, 'like', '%' . $value . '%');
            }
        }

        return $builder;
    }
}
