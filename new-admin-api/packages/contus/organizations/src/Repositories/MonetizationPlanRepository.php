<?php

namespace Contus\Organizations\Repositories;

use Contus\Base\Repository;
use Contus\Organizations\Model\OrganizationMonitizationPlan;

class MonetizationPlanRepository extends Repository {

    protected $plan;

    public function __construct(OrganizationMonitizationPlan $organizationMonitizationPlan) {
        parent::__construct();
        $this->plan = $organizationMonitizationPlan;
    }

    public function prepareGrid() {
        $this->setGridModel($this->plan)->setEagerLoadingModels(['contentSets']);
        return $this;
    }
}
