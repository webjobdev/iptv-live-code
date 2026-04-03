<?php

namespace Contus\Settings\Repositories\Extensions;

use Contus\Base\Helpers\StringLiterals;
use Contus\Base\Repository;
use Contus\Settings\Model\PaymentService;
use Contus\Settings\Model\PaymentServiceCurrencyConverter;
use Contus\Settings\Model\PlayBack;

class PlayBackTokenRepositoriy extends Repository
{
    protected $_pbt;

    public function __construct(PlayBack $pbt)
    {
        parent::__construct();
        $this->_pbt = $pbt;
    }

    public function postAdd()
    {
        return $this->postCreate($this->request->all());
    }

    public function postCreate($requstdata)
    {
        $this->setRules([
            'name' => 'required',
            'type' => 'nullable|required',
            'secret_key' => 'required',
            'token_time' => 'required',
            'secret_generation_number' => 'nullable',
            'ignore_device_ip_verification' => 'nullable',
            'rsa_private_key' => 'nullable',
            'url_format' => 'nullable',
            'is_active' => 'nullable',

        ]);
        $this->_validate();

        $create = new PlayBack();

        $create->name = $requstdata['name'];
        $create->type = $requstdata['type'];
        $create->secret_key = $requstdata['secret_key'] ?? null;
        $create->token_time = $requstdata['token_time'] ?? null;
        $create->secret_generation_number = $requstdata['secret_generation_number'] ?? null;
        $create->ignore_device_ip_verification = $requstdata['ignore_device_ip_verification'] ?? null;
        $create->rsa_private_key = $requstdata['rsa_private_key'] ?? null;
        $create->url_format = $requstdata['url_format'] ?? null;
        $create->is_active = isset($this->request->is_active) ? 1 : 0;

        $create->save();

        return 'success';
    }

    public function postEdit($id)
    {
        if ($id) {
            $edit = $this->_pbt->find($id);

            $this->setRules([
                'name' => 'required',
                'type' => 'nullable|required',
                'secret_key' => 'required',
                'token_time' => 'required',
                'secret_generation_number' => 'nullable',
                'ignore_device_ip_verification' => 'nullable',
                'rsa_private_key' => 'nullable',
                'url_format' => 'nullable',
                'is_active' => 'nullable',
            ]);

            $this->validate($this->request, $this->getRules());

            $edit->name = $this->request->name;
            $edit->type = $this->request->type;
            $edit->secret_key = $this->request->secret_key;
            $edit->token_time = $this->request->token_time;
            $edit->secret_generation_number = $this->request->secret_generation_number;
            $edit->ignore_device_ip_verification = $this->request->ignore_device_ip_verification;
            $edit->rsa_private_key = $this->request->rsa_private_key;
            $edit->url_format = $this->request->url_format;
            $edit->is_active = $this->request->is_active ? 1 : 0;

            $edit->save();

            return true;

        } else {
            return false;
        }
    }

    public function postToggle($id)
    {
        if (!empty($id)) {
            $toggle = $this->_pbt->find($id);

            $toggle->is_active = $this->request->is_active ? 1 : 0;
            $toggle->save();

            return response()->json([
                'success' => true,
                'message' => 'Data Update Successfully.',
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Invalid ID'], 400);
    }

    public function prepareGrid()
    {
        $this->setGridModel($this->_pbt);
        return $this;
    }

    public function getGridHeadings()
    {
        return [
            'heading' => [
                ['name' => 'S.No', 'S.No' => 'SNo', 'sort' => false],
                ['name' => 'Name', 'value' => '', 'sort' => true],
                ['name' => 'Type', 'value' => '', 'sort' => true],
                ['name' => 'Token Time (Min)', 'value' => '', 'sort' => true],
                ['name' => 'Assigned URLs', 'value' => '', 'sort' => true],
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