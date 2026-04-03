<?php

namespace App\Http\Middleware;

use Closure;

class SwitchLanguage
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;
    
    /**
     * Create a new filter instance.
     *
     * @param  Guard  $auth
     * @return void
     */
    public function __construct()
    {
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(app()->request->session()->has('site_language')) {
              $lang = app()->request->session()->get('site_language');
              app()->setLocale($lang);
        }

        $response = $next($request);
        $response->header('pragma', 'no-cache');
        $response->header('Cache-Control', 'no-store,no-cache, must-revalidate, post-check=0, pre-check=0');

        return $response;
    }
}