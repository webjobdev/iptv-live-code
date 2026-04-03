<?php

/**
 * Countries repository
 *
 * To manage the Countrieslist management such as create, edit
 *
 * @name CountriesRepository
 * @version 1.0
 * @author Contus Team <developers@contus.in>
 * @copyright Copyright (C) 2018 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Geofencing\Repositories;
use Contus\Base\Repository as BaseRepository;
use Contus\Geofencing\Models\GeoCountries;

class CountriesRepository extends BaseRepository
{

    public function getCountries(){
        $countries=GeoCountries::where('is_active',1)->get();
        return $countries;
    }
}