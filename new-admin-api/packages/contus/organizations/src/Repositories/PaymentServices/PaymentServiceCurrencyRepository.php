<?php

namespace Contus\Organizations\Repositories\PaymentServices;

use Contus\Base\Helpers\StringLiterals;
use Contus\Base\Repository;
use Contus\Organizations\Model\OrganizationCurrencies;
use Contus\Settings\Model\PaymentServiceCurrency;

class PaymentServiceCurrencyRepository extends Repository
{
    protected $_currency;

    public function __construct(PaymentServiceCurrency $psCurrency)
    {
        parent::__construct();
        $this->_currency = $psCurrency;
    }

    public function postToggle()
    {
        $organization_id = $this->request->get('organization_id');
        $currency_id = $this->request->get('currency_id');
        $is_active = $this->request->get('is_active', 0);

        if ($organization_id && $currency_id) {
            OrganizationCurrencies::updateOrCreate(
                ['organization_id' => $organization_id, 'currency_id' => $currency_id],
                ['is_active' => $is_active]
            );
            return true;
        }
        return false;
    }

    public function prepareGrid()
    {
        $this->setGridModel($this->_currency)
            ->setEagerLoadingModels(['organizationCurrencies', 'organizationCurrencies.organizationDetail']);
        return $this;
    }

    protected function updateGridQuery($builder)
    {
        $organization_id = $this->request->get('organization_id') ?? $this->request->get('id');
        if ($organization_id) {
            $builder->whereHas('organizationCurrencies', function ($query) use ($organization_id) {
                $query->where('organization_id', $organization_id)->where('is_active', 1);
            });
        }
        return $builder;
    }

    public function getGridHeadings()
    {
        return [
            'heading' => [
                ['name' => 'S.No', 'S.No' => 'SNo', 'sort' => false],
                ['name' => 'Currency Code', 'value' => '', 'sort' => true],
                ['name' => 'Currency Symbol', 'value' => '', 'sort' => true],
                ['name' => 'Position', 'value' => '', 'sort' => true],
                ['name' => 'Sample Value', 'value' => '', 'sort' => true],
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
            if (in_array($key, ['is_active', 'is_parental', 'default']) && ($value === 'all' || is_null($value) || $value === '')) {
                continue;
            }

            // Only apply filters for keys that exist on this model's table
            if (in_array($key, ['currency_code', 'currency_symbol', 'position', 'sample'])) {
                $builderCoupon = $builderCoupon->where($key, 'like', "%$value%");
            }
        }

        return $builderCoupon;
    }
}