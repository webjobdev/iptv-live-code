<?php

namespace Contus\Settings\Repositories\Extensions;

use Contus\Base\Helpers\StringLiterals;
use Contus\Base\Repository;
use Contus\Settings\Model\DeviceRedirect;
use Contus\Settings\Model\PaymentService;

class DeviceRedirectRepositoriy extends Repository
{
    protected $_device;

    public function __construct(DeviceRedirect $deviceRedirect)
    {
        parent::__construct();
        $this->_device = $deviceRedirect;
    }

    public function postAdd()
    {
        return $this->postCreate($this->request->all());
    }

    public function postCreate($requstdata)
    {
        $this->setRules([
            'name' => 'nullable|required',
            'url' => 'nullable|required',
        ]);
        $this->_validate();

        $create = new DeviceRedirect();

        $create->name = $requstdata['name'];
        $create->url = $requstdata['url'];
        $create->is_active = isset($this->request->is_active) ? 1 : 0;

        $create->save();

        return 'success';
    }

    public function postEdit($id)
    {
        if ($id) {
            $edit = $this->_device->find($id);

            $this->setRules([
                'name' => 'nullable|required',
                'url' => 'nullable|required',
            ]);

            $this->validate($this->request, $this->getRules());

            $edit->name = $this->request->name;
            $edit->url = $this->request->url;
            $edit->is_active = isset($this->request->is_active) ? 1 : 0;

            $edit->save();

            return true;

        } else {
            return false;
        }
    }

    public function prepareGrid()
    {
        $this->setGridModel($this->_device);
        return $this;
    }

    public function getGridHeadings()
    {
        return [
            'heading' => [
                ['name' => 'S.No', 'S.No' => 'SNo', 'sort' => false],
                ['name' => 'Name', 'value' => '', 'sort' => true],
                ['name' => 'Url', 'value' => '', 'sort' => true],
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