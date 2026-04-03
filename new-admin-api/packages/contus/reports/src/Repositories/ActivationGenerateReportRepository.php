<?php

namespace Contus\Reports\Repositories;

use Contus\Base\Helpers\StringLiterals;
use Contus\Base\Repository;
use Contus\Reports\Model\Activation;

class ActivationGenerateReportRepository extends Repository
{
    protected $_sactivation;
    public function __construct(Activation $activation)
    {
        parent::__construct();
        $this->_sactivation = $activation;
    }

    public function prepareGrid()
    {
        $this->setGridModel($this->_sactivation)
            ->setEagerLoadingModels(['GetOrg', 'UserList', 'PlanList', 'GetUser']);
        return $this;
    }

    public function getGridHeadings()
    {
        return [
            'heading' => [
                ['name' => 'S.No', 'value' => 'SNo', 'sort' => false],
                ['name' => 'ID', 'value' => '', 'sort' => true],
                ['name' => 'Name', 'value' => '', 'sort' => false],
                ['name' => 'Status', 'value' => '', 'sort' => false],
                ['name' => 'Created', 'value' => '', 'sort' => false],
                ['name' => 'Created By', 'value' => '', 'sort' => false],
                ['name' => 'Organization', 'value' => '', 'sort' => false],
                ['name' => 'Report Type', 'value' => '', 'sort' => false],
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