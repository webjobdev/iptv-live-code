<?php

namespace Contus\Organizations\Repositories\PaymentServices;

use Contus\Base\Helpers\StringLiterals;
use Contus\Base\Repository;
use Contus\Organizations\Model\OrganizationDetail;
use Contus\Organizations\Model\OrganizationPaymentService;
use Contus\Settings\Model\PaymentService;

class PaymentServiceRepository extends Repository
{
    protected $_services;
    protected $_OrgDtl;

    public function __construct(PaymentService $pametService, OrganizationDetail $OrgDtl)
    {
        parent::__construct();
        $this->_OrgDtl = $OrgDtl;
        $this->_services = $pametService;
    }

    public function postToggle($id)
    {
        if (!empty($id)) {
            $banner = $this->_services->find($id);

            $banner->is_active = $this->request->is_active == 1 ? 1 : 0;
            $banner->save();

            return response()->json([
                'success' => true,
                'message' => 'Data Update Successfully.',
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Invalid ID'], 400);
    }

    public function postDefault()
    {
        $organization_id = $this->request->get('organization_id');
        $payment_service_id = $this->request->get('payment_service_id');
        $default = $this->request->get('default', 0);

        if ($organization_id && $payment_service_id) {
            OrganizationPaymentService::updateOrCreate(
                ['organization_id' => $organization_id, 'payment_service_id' => $payment_service_id],
                ['default' => $default]
            );
            return true;
        }
        return false;
    }

    public function postEdit()
    {
        // $organization_id = $this->request->get('organization_id');
        $id = $this->request->get('id');
        $default = $this->request->get('default', 0);

        if ($id) {
            $this->_services->updateOrCreate(
                ['id' => $id],
                ['default' => $default]
            );
            return true;
        }
        return false;
    }

    public function prepareGrid()
    {
        $this->setGridModel($this->_services)
            ->setEagerLoadingModels(['SystemDefault', 'organizationDefault', 'organizationDefault.subscribers']);
        return $this;
    }

    public function getGridHeadings()
    {
        return [
            'heading' => [
                ['name' => 'S.No', 'S.No' => 'SNo', 'sort' => false],
                ['name' => 'Service Type', 'value' => '', 'sort' => true],
                ['name' => 'Mode', 'value' => '', 'sort' => true],
                ['name' => 'Status', 'value' => '', 'sort' => true],
                ['name' => 'System Default', 'value' => '', 'sort' => true],
                ['name' => 'Actions', 'value' => '', 'sort' => true],
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