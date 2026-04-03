<?php

namespace Contus\Settings\Repositories\PaymentService;

use Contus\Base\Helpers\StringLiterals;
use Contus\Base\Repository;
use Contus\Settings\Model\PaymentService;
use Contus\Settings\Model\PaymentServiceCurrencyConverter;

class PaymentServiceCurrencyConverterRepository extends Repository
{
    protected $_converter;

    public function __construct(PaymentServiceCurrencyConverter $psCurrencyConverter)
    {
        parent::__construct();
        $this->_converter = $psCurrencyConverter;
    }

    public function postAdd()
    {
        return $this->postCreate($this->request->all());
    }

    public function postCreate($requstdata)
    {
        $this->setRules([
            'token' => 'required',
            // 'refresh_rate_mode' => 'nullable|required',
            // 'refresh_rate' => 'nullable',
            // 'refresh_rate_unit' => 'nullable',
        ]);
        $this->_validate();

        $create = new PaymentServiceCurrencyConverter();

        $create->token = $requstdata['token'];
        $create->refresh_rate_mode = $requstdata['refresh_rate_mode'];
        $create->refresh_rate = $requstdata['refresh_rate'] ?? null;
        $create->refresh_rate_unit = $requstdata['refresh_rate_unit'] ?? null;

        $create->save();

        return 'success';
    }

    public function postEdit($id)
    {
        if ($id) {
            $edit = $this->_converter->find($id);

            $this->setRules([
                'token' => 'nullable|required',
                'refresh_rate_mode' => 'nullable|required',
                'refresh_rate' => 'nullable',
                'refresh_rate_unit' => 'nullable',
            ]);

            $this->validate($this->request, $this->getRules());

            $edit->token = $this->request->token;
            $edit->refresh_rate_mode = $this->request->refresh_rate_mode;
            $edit->refresh_rate = $this->request->refresh_rate;
            $edit->refresh_rate_unit = $this->request->refresh_rate_unit;

            $edit->save();

            return true;

        } else {
            return false;
        }
    }

    public function prepareGrid()
    {
        $this->setGridModel($this->_converter);
        return $this;
    }

    public function getGridHeadings()
    {
        return [
            'heading' => [
                ['name' => 'S.No', 'S.No' => 'SNo', 'sort' => false],
                ['name' => 'Token', 'value' => '', 'sort' => true],
                ['name' => 'Refresh Rate (Mode)', 'value' => '', 'sort' => true],
                ['name' => 'Refresh Rate', 'value' => '', 'sort' => true],
                ['name' => 'Refresh Rate Unit', 'value' => '', 'sort' => true],
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