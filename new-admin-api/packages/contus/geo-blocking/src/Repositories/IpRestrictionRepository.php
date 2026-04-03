<?php

namespace Contus\GeoBlocking\Repositories;

use Contus\Base\Repository;
use Contus\GeoBlocking\Model\IpRestrictions;

class IpRestrictionRepository extends Repository
{

    protected $_ipRestrictions;

    public function __construct(IpRestrictions $ipRestriction)
    {
        parent::__construct();
        $this->_ipRestrictions = $ipRestriction;
    }

    public function prepareGrid()
    {
        $data = $this->setGridModel($this->_ipRestrictions);
        return $this;
    }


    public function addIpRestrictions()
    {
        $this->setRules([
            'mode' => 'required',
            'ip_address' => 'required',
            'ip_restrictions' => 'required',
        ]);

        $this->_validate();

        $ipRestriction = new IpRestrictions();
        $ipRestriction->mode = $this->request->input('mode');

        $rawIpString = $this->request->input('ip_address'); // string
        $ipArray = array_filter(array_map('trim', explode(',', $rawIpString)));
        $ipRestriction->ip_address = $ipArray;

        $ipRestriction->geo_ip_status = $this->request->input('ip_restrictions') == 'true' ? '1' : '0';
        $ipRestriction->save();

        return response()->json([
            'success' => true,
            'message' => 'IP Restrictions Added Successfully.',
        ]);
    }

    public function editIpRestrictions($id)
    {
        $this->setRules([
            'name' => 'required',
            'ip_address' => 'required',
            'ip_restrictions' => 'required',
        ]);
        $this->_validate();

        $ipRestriction = IpRestrictions::find($id);
        $ipRestriction->mode = $this->request->input('mode');
        $ipRestriction->ip_address = explode(",", $this->request->input('ip_address'));
        $ipRestriction->geo_ip_status = $this->request->input('ip_restrictions') == 'true' ? '1' : '0';
        $ipRestriction->save();

        return response()->json([
            'success' => true,
            'message' => 'IP Restrictions Updated Successfully.',
        ]);
    }

    public function getGridHeadings()
    {
        return [
            'heading' => [
                ['name' => 'S.No', 'S.No' => 'SNo', 'sort' => false],
                ['name' => 'Mode', 'value' => 'mode', 'sort' => false, 'class' => false],
                ['name' => 'IP Address', 'value' => 'ip_address', 'sort' => false, 'class' => false],
                ['name' => 'IP Status', 'value' => 'geo_ip_status', 'sort' => false, 'class' => false],
                ['name' => 'Action', 'value' => '', 'sort' => false],
            ]
        ];
    }
}
