<?php

namespace Contus\Reports\Repositories;

use Contus\Base\Helpers\StringLiterals;
use Contus\Base\Repository;
use Contus\Reports\Model\SubscriberReports;

class SubscriberGenerateReportRepository extends Repository
{
    protected $_subscriberReports;
    public function __construct(SubscriberReports $subscriberReports)
    {
        parent::__construct();
        $this->_subscriberReports = $subscriberReports;
    }

    public function prepareGrid()
    {
        $this->setGridModel($this->_subscriberReports)
            ->setEagerLoadingModels(['organization', 'GetUser']);
            
        return $this;
    }

    public function getGridHeadings()
    {
        return [
            'heading' => [
                ['name' => 'S.No', 'value' => 'SNo', 'sort' => false],
                ['name' => 'Number', 'value' => '', 'sort' => true],
                ['name' => 'Name', 'value' => '', 'sort' => false],
                ['name' => 'Created', 'value' => '', 'sort' => false],
                ['name' => 'Created By', 'value' => '', 'sort' => false],
                ['name' => 'Organization', 'value' => '', 'sort' => false],
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