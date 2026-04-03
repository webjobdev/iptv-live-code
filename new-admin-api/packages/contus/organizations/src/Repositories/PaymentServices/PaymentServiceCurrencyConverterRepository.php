<?php

namespace Contus\Organizations\Repositories\PaymentServices;

use Contus\Base\Helpers\StringLiterals;
use Contus\Base\Repository;
use Contus\Organizations\Model\OrganizationDetail;
use Contus\Settings\Model\PaymentService;
use Contus\Settings\Model\PaymentServiceCurrencyConverter;

class PaymentServiceCurrencyConverterRepository extends Repository
{
    protected $_converter;
    protected $_OrgDtl;

    public function __construct(PaymentServiceCurrencyConverter $psCurrencyConverter, OrganizationDetail $OrgDtl)
    {
        parent::__construct();
        $this->_OrgDtl = $OrgDtl;
        $this->_converter = $psCurrencyConverter;
    }

    public function postEdit($id)
    {
        if ($id) {
            $edit = $this->_OrgDtl->find($id);
            // dd($edit);

            $this->setRules([
                'currency_converter_system_default' => 'nullable|required',
            ]);

            $this->validate($this->request, $this->getRules());

            $edit->currency_converter_system_default = $this->request->currency_converter_system_default;
            $edit->save();

            return true;

        } else {
            return 'flase';
        }
    }

    public function postToggle($id)
    {
        if (!empty($id)) {
            $banner = $this->_converter->find($id);

            $banner->is_active = $this->request->is_active ? 1 : 0;
            $banner->save();

            return response()->json([
                'success' => true,
                'message' => 'Data Update Successfully.',
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Invalid ID'], 400);
    }


    public function prepareGrid()
    {
        $this->setGridModel($this->_converter)
            ->setEagerLoadingModels(['organizationDetails']);
        return $this;
    }

    public function getGridHeadings()
    {
        return [
            'heading' => [
                ['name' => 'S.No', 'S.No' => 'SNo', 'sort' => false],
                ['name' => 'Token', 'value' => '', 'sort' => true],
                ['name' => 'Refresh Rate', 'value' => '', 'sort' => true],
                ['name' => 'Requests', 'value' => '', 'sort' => true],
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