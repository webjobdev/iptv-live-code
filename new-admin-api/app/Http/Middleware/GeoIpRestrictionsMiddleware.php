<?php

namespace App\Http\Middleware;

use Closure;
use Contus\AppApi\Api\Controllers\AppApiController;
use Contus\GeoBlocking\Model\GeoRestrictions;
use Contus\GeoBlocking\Model\IpRestrictions;
use Contus\Tvshow\Model\TvShow;
use Contus\Vod\Model\VideoOnDemad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Stevebauman\Location\Location;
use Tymon\JWTAuth\Facades\JWTAuth;

class GeoIpRestrictionsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        // dd($user);
        $userIp = $user->ip_address;
        // dd($userIp);

        // IP RESTRICTION CHECK
        $ipRestrictions = IpRestrictions::whereJsonContains('ip_address', $userIp)->where('geo_ip_status', '1')->where('mode', 'block')->get();
        // dd($ipRestrictions);
        if (!$ipRestrictions->isEmpty()) {
            $request->merge([
                'ip_restriction' => ['mode' => 'block']
            ]);
        } else {
            $request->merge([
                'ip_restriction' => ['mode' => 'allow']
            ]);
        }

        // GEO RESTRICTION CHECK
        // $ipData = \Location::get($userIp);
        $ipData = \Location::get('2409:40c1:10b6:cc3b:148e:cbff:fe07:1b96');
        $userCountry = $ipData->countryName;
        // dd($userCountry);

        $geoRestrictions = GeoRestrictions::whereJsonContains('countries', strtolower($userCountry))
            ->where('geo_protection_status', 1)
            ->where('mode', 'block')
            ->get();

        if (!$geoRestrictions->isEmpty()) {
            $request->merge([
                'geo_restriction' => ['mode' => 'block']
            ]);
        } else {
            $request->merge([
                'geo_restriction' => ['mode' => 'allow']
            ]);
        }

        return $next($request);
    }
}
