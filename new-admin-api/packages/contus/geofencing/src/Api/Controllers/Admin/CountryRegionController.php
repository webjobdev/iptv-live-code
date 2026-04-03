<?php

/**
 * CountryRegionController
 *
 * To manage the countries,regions such as create, edit and delete
 *
 * @name CountryRegionController
 * @version 1.0
 * @author Contus Team <developers@contus.in>
 * @copyright Copyright (C) 2018 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Geofencing\Api\Controllers\Admin;

use Contus\Geofencing\Repositories\RegionsRepository;
use Contus\Geofencing\Repositories\CountriesRepository;
use Contus\Geofencing\Repositories\CountriesRegionsRepository;
use Contus\Base\ApiController;
use Contus\Base\Helpers\StringLiterals;
use Illuminate\Http\Request;
use Contus\Geofencing\Models\GeoSettings;
use Contus\Geofencing\Models\GeoCountries;
use Contus\Geofencing\Models\GlobalAllowedCountries;

class CountryRegionController extends ApiController
{

    /**
     * Class construct method initialization
     */
    public function __construct(CountriesRepository $countriesRepository, RegionsRepository $regionsRepository,CountriesRegionsRepository $countriesRegionsRepository)
    {
        parent::__construct();
        $this->countriesRegionsRepository = $countriesRegionsRepository;
        $this->countriesRepository = $countriesRepository;
        $this->regionsRepository = $regionsRepository;
    }

    /**
     * get Information for create form
     * return various information request by the form
     *
     * @return \Illuminate\Http\Response
     */
    public function getInfo() {
        $selectedCountries = $this->countriesRegionsRepository->getInfo();
        return $this->getSuccessJsonResponse ( [ 
            'info' => [ 
                'geo_setting' => GeoSettings::where('is_active', 1)->first(),
                'selected_countries' => $selectedCountries
               ] 
            ] );   
    }

    /**
     * Method to get basic informations
     *
     * @vendor Contus
     * @return Illuminate\Http\Response
     */
    public function getCountries()
    {
        return $this->getSuccessJsonResponse([
            'info' => [
                'countries' => $this->countriesRepository->getCountries(),
            ],
        ]);

    }
    /**
     * Method to get basic informations
     *
     * @vendor Contus
     * @return Illuminate\Http\Response
     */
    public function getRegions($id)
    {
        return $this->getSuccessJsonResponse([
            'info' => [
                'regions' => $this->regionsRepository->getRegions($id),
            ],
        ]);

    }
    /**
     * Method to get basic informations
     *
     * @vendor Contus
     * @return Illuminate\Http\Response
     */
    public function getRegionsDetail()
    {
        return $this->getSuccessJsonResponse([
            'info' => [
                'regions' => $this->regionsRepository->getRegionsDetail(),
            ],
        ]);

    }
    /**
     * Get Geo Settings Type
     *
     * This function is to save Either for individual video or global videos or allow all countries
     * 
     */
    public function getSettingType(){
        if($this->request->geoType != null){
            GeoSettings::where('is_active',1)->update(['is_active'=>0]);
            GeoSettings::where('type',$this->request->geoType)->update(['is_active'=>1]);}
        if($this->request->allowedData != null){
            GlobalAllowedCountries::truncate();
            foreach($this->request->allowedData as $key=>$value){
                    $regions=[];
                    $allowedCountries =new GlobalAllowedCountries();
                    $countryName=Geocountries::where('short_code',$key)->first();
                    $allowedCountries->country_id = $countryName['id'];
                    $allowedCountries->country_name = $countryName['country_name'];
                    $allowedCountries->country_short_code = $key;
                    foreach($value as $region){
                        $regions[] =$region['short_code'];
                    }
                    $allowedCountries->regions = $regions;
                    $allowedCountries ->save();
            }
        }    
        // $this->request->session()->flash('success', trans('geofencing::geofencing.added'));
            return $this->getSuccessJsonResponse(['data' => ''], trans('geofencing::geofencing.added'));        
    }
    public function getGlobalSettings(){
            return GlobalAllowedCountries::get();   
    }
}
