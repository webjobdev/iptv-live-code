<?php

namespace Contus\Settings\Repositories\PaymentService;

use Contus\Base\Helpers\StringLiterals;
use Contus\Base\Repository;
use Contus\Settings\Model\PaymentService;
use Contus\Settings\Model\PaymentServiceCurrency;

class PaymentServiceCurrencyRepository extends Repository
{
    protected $_currency;

    public function __construct(PaymentServiceCurrency $psCurrency)
    {
        parent::__construct();
        $this->_currency = $psCurrency;
    }

    public function postAdd()
    {
        return $this->postCreate($this->request->all());
    }

    public function postCreate($requstdata)
    {
        $this->setRules([
            'currency_code' => 'required',
            // 'currency_symbol' => 'nullable|required',
            // 'position' => 'nullable|required',
            'sample' => 'required',
        ]);
        $this->_validate();

        $create = new PaymentServiceCurrency();

        $ex = $requstdata['currency_symbol'] . ' ' . ($requstdata['sample'] ?? '');

        $create->currency_code = $requstdata['currency_code'];
        $create->currency_symbol = $requstdata['currency_symbol'];
        $create->position = $requstdata['position'];
        $create->sample = $ex;

        $create->save();

        return 'success';
    }

    public function postEdit($id)
    {
        if ($id) {
            $edit = $this->_currency->find($id);

            $this->setRules([
                'currency_code' => 'nullable|required',
                // 'currency_symbol' => 'nullable|required',
                // 'position' => 'nullable|required',
                'sample' => 'nullable|required',
            ]);

            $this->validate($this->request, $this->getRules());

            $ex = $this->request->currency_symbol . ' ' . ($this->request->sample ?? '');

            $edit->currency_code = $this->request->currency_code;
            $edit->currency_symbol = $this->request->currency_symbol;
            $edit->position = $this->request->position;
            $edit->sample = $ex;

            $edit->save();

            return true;

        } else {
            return false;
        }
    }

    public function prepareGrid()
    {
        $this->setGridModel($this->_currency);
        return $this;
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
            if (in_array($key, ['is_active', 'is_parental']) && $value === 'all') {
                continue;
            }

            $builderCoupon = $builderCoupon->where($key, 'like', "%$value%");
        }

        return $builderCoupon;
    }
}