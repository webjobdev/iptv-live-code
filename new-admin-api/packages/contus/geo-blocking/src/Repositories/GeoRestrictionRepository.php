<?php

namespace Contus\GeoBlocking\Repositories;

use Contus\Base\Repository;
use Contus\GeoBlocking\Model\GeoRestrictions;

class GeoRestrictionRepository extends Repository
{

    protected $_geoRestrictions;

    public function __construct(GeoRestrictions $geoRestriction)
    {
        parent::__construct();
        $this->_geoRestrictions = $geoRestriction;
    }

    public function prepareGrid()
    {
        $data = $this->setGridModel($this->_geoRestrictions);
        return $this;
    }


    public function addGeoRestriction()
    {
        $this->setRules([
            'name' => 'required',
            'type' => 'required',
            'ip_restriction' => 'required',
            'countries' => 'required',
            'overide' => 'required'
        ]);

        $this->_validate();

        $geoRestriction = new GeoRestrictions();
        $geoRestriction->name = $this->request->input('name');
        $geoRestriction->type = $this->request->input('type');
        $geoRestriction->geo_ip_status = $this->request->input('geo_restrictions') == 'true' ? '1' : '0';
        // $geoRestriction->geo_protection_status = $this->request->input('ip_restriction') == 'true' ? '1' : '0';
        $geoRestriction->geo_protection_status = $this->request->input('ip_restriction') == 'true' ? '1' : '0';
        $cntries = [];
        $reqCountry = $this->request->input('countries');
        foreach ($reqCountry as $cntry) {
            $cntries[] = $cntry;
        }
        $geoRestriction->countries =  $cntries;
        $geoRestriction->mode = $this->request->input('mode');
        $geoRestriction->override_geo_restrictions = $this->request->input('overide');
        $geoRestriction->save();

        return response()->json([
            'success' => true,
            'message' => 'Geo Restrictions Added Successfully.',
        ]);
    }

    public function editGeoRestriction($id)
    {
        $this->setRules([
            'name' => 'required',
            'type' => 'required',
            'ip_restriction' => 'required',
            'countries' => 'required',
            'overide' => 'required'
        ]);

        $this->_validate();

        $geoRestriction = GeoRestrictions::find($id);
        $geoRestriction->name = $this->request->input('name');
        $geoRestriction->type = $this->request->input('type');
        $geoRestriction->geo_ip_status = $this->request->input('geo_restrictions') == 'true' ? '1' : '0';
        // $geoRestriction->geo_protection_status = $this->request->input('ip_restriction') == 'true' ? '1' : '0';
        $geoRestriction->geo_protection_status = $this->request->input('ip_restriction');
        $cntries = [];
        $reqCountry = $this->request->input('countries');
        foreach ($reqCountry as $cntry) {
            $cntries[] = $cntry;
        }
        $geoRestriction->countries =  $cntries;
        $geoRestriction->mode = $this->request->input('mode');
        $geoRestriction->override_geo_restrictions = $this->request->input('overide');
        $geoRestriction->save();

        return response()->json([
            'success' => true,
            'message' => 'Geo Restrictions Added Successfully.',
        ]);
    }

    public function getGridHeadings()
    {
        return ['heading' => [
            ['name' => 'S.No', 'S.No' => 'SNo', 'sort' => false],
            ['name' => 'Name', 'value' => 'name', 'sort' => false, 'class' => false],
            ['name' => 'Type', 'value' => 'type', 'sort' => false, 'class' => false],
            ['name' => 'IP Status', 'value' => 'geo_ip_status', 'sort' => false, 'class' => false],
            ['name' => 'Protection Status', 'value' => 'geo_protection_status', 'sort' => false],
            // ['name' => trans('geo-blocking::index.status'), 'value' => '', 'sort' => false],
            ['name' => 'Action', 'value' => '', 'sort' => false],
        ]];
    }
}
