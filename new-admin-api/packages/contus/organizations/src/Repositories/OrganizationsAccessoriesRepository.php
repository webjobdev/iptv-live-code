<?php

namespace Contus\Organizations\Repositories;

use Contus\Base\Helpers\StringLiterals;
use Contus\Base\Repository;
use Contus\Organizations\Model\Accessories;
use Contus\Organizations\Model\OrganizationMonitizationPlan;
use Illuminate\Support\Facades\Auth;

class OrganizationsAccessoriesRepository extends Repository
{

    protected $_accessories;

    public function __construct(Accessories $accessories)
    {
        parent::__construct();
        $this->_accessories = $accessories;
    }

    public function postAdd()
    {
        return $this->postCreate($this->request->all());
    }

    public function postCreate($requestData)
    {
        $user = Auth::user();

        $this->setRules([
            'organization_id' => 'required',
            'accessories' => 'required',
            'accessories_type' => 'required',
            'identifier' => 'required',
            'identifier_auto' => 'required',
            'description' => 'required',
            'currency' => 'required',
            'price' => 'required',
            'is_active' => 'required',
        ]);

        $this->_validate();

        $acs = new Accessories();

        $acs->organization_id = $requestData['organization_id'];
        $acs->accessories = $requestData['accessories'];
        $acs->accessories_type = $requestData['accessories_type'];
        $acs->identifier = $requestData['identifier'];
        $acs->identifier_auto = $requestData['identifier_auto'] ? 1 : 0;
        $acs->description = $requestData['description'];
        $acs->currency = $requestData['currency'];
        $acs->price = $requestData['price'];
        $acs->by_user = $user->id;
        $acs->is_active = $requestData['is_active'] ? 1 : 0;

        if ($acs->save()) {
            return 'success';
        } else {
            return 'error';
        }
    }

    public function postEdit($id)
    {
        if (!empty($id)) {

            $acsEdit = $this->_accessories->find($id);
            $user = Auth::user();

            $this->setRules([
                // 'organization_id' => 'required',
                'accessories' => 'required',
                'accessories_type' => 'required',
                'identifier' => 'required',
                'identifier_auto' => 'required',
                'description' => 'required',
                'currency' => 'required',
                'price' => 'required',
                'is_active' => 'required',
            ]);

            $this->validate($this->request, $this->getRules());

            // $acsEdit->organization_id = $this->request->organization_id;
            $acsEdit->accessories = $this->request->accessories;
            $acsEdit->accessories_type = $this->request->accessories_type;
            $acsEdit->identifier = $this->request->identifier;
            $acsEdit->identifier_auto = $this->request->identifier_auto ? 1 : 0;
            $acsEdit->description = $this->request->description;
            $acsEdit->currency = $this->request->currency;
            $acsEdit->price = $this->request->price;
            $acsEdit->is_active = $this->request->is_active ? 1 : 0;
            $acsEdit->by_user = $user->id;

            $acsEdit->save();

            return 'success';
        } else {
            return
                'false';
        }
    }

    public function toggleEdit($id)
    {
        if (!empty($id)) {
            $toggle = $this->_accessories->find($id);

            $toggle->is_active = $this->request->is_active ? 1 : 0;
            $toggle->save();

            return 'true';
        } else {
            return 'false';
        }
    }



    public function prepareGrid()
    {
        $this->setGridModel($this->_accessories)->setEagerLoadingModels(['ByUser']);
        return $this;
    }

    public function getGridHeadings()
    {
        return [
            'heading' => [
                ['name' => 'S.No', 'value' => 'SNo', 'sort' => false],
                ['name' => 'Name', 'value' => '', 'sort' => true],
                ['name' => 'Type', 'value' => '', 'sort' => true],
                ['name' => 'Currency', 'value' => '', 'sort' => false],
                ['name' => 'Price', 'value' => '', 'sort' => false],
                ['name' => 'User', 'value' => '', 'sort' => true],
                ['name' => 'Status', 'value' => '', 'sort' => false],
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
