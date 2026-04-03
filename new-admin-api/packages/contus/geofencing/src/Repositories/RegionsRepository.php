<?php

/**
 * Regions repository
 *
 * To manage the Regions list management such as create, edit
 *
 * @name RegionsRepository
 * @version 1.0
 * @author Contus Team <developers@contus.in>
 * @copyright Copyright (C) 2018 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Geofencing\Repositories;
use Contus\Base\Repository as BaseRepository;
use Contus\Geofencing\Models\GeoRegions;
use Contus\Geofencing\Models\GlobalAllowedCountries;
use Contus\Geofencing\Models\GeoIndividualAllowedCountries;

class RegionsRepository extends BaseRepository{
    public function __construct(){
        parent::__construct();
        $this->geoRegions = new GeoRegions();
        $this->globalGeoModel = new GlobalAllowedCountries();
        $this->individualGeoModel = new GeoIndividualAllowedCountries();
    }
    /**
     * This function used to get all the regions based on the country selected.
     *
     * @return number|\Contus\Customer\Models\GeoRegions
     */
    public function getRegions($id){
        $regions=GeoRegions::where('is_active',1)->where('country_id',$id)->get();
        return $regions;
    }
    /**
     * This function used to get the region details based on the country code and the region code.
     *
     * @return mixed|\Contus\Customer\Models\GeoRegions
     */
    public function getRegionsDetail(){
        $type = $model = '';
        $type = $this->request->type;
        $videoId = (string)$this->request->videoID;
        $model = (isset($type) && $type == 'individual') ? $this->individualGeoModel:  $this->globalGeoModel;
        if($videoId != null){
        $regionsDetail=$model->select('regions')->where('video_id',$videoId)->where('country_id',$this->request->country_id)->pluck('regions')->toArray();
        }else{
            $regionsDetail=$model->select('regions')->where('country_id',$this->request->country_id)->pluck('regions')->toArray();
        }
        return (!empty($regionsDetail)) ? $this->geoRegions->whereIn('short_code', $regionsDetail[0])->where('country_id',$this->request->country_id)->get() : null;
    }
}