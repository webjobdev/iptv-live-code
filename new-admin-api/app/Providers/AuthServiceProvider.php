<?php

namespace App\Providers;

use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;
use Contus\User\Models\User;
use Contus\Customer\Models\Customer;
use Contus\Organizations\Model\OrgSubscribers;
use Illuminate\Support\Facades\Log;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = ['App\Model' => 'App\Policies\ModelPolicy'];

    /**
     * Register any application authentication / authorization services.
     *
     * @param \Illuminate\Contracts\Auth\Access\Gate $gate
     * @return void
     */
    public function boot(GateContract $gate)
    {
        // if (env ( "LS_HOST" ) == 1) {
        //     if (($this->app ['request']->header ( 'host' ) === env ( "LS_TYPE_ADMIN" )) === false) {
        //         config ()->set ( 'auth.model', Customer::class );
        //         config ()->set ( 'auth.providers.users.table', 'customers' );
        //         config ()->set ( 'session.cookie', '_ls_s' );
        //     }
        // }else{
        //     if (strpos ( $this->app ['request']->path (), 'admin' ) === false) {
        //         config ()->set ( 'auth.providers.users.model', Customer::class );
        //         config ()->set ( 'auth.providers.users.table', 'customers' );
        //         config ()->set ( 'session.cookie', '_ls_s' );
        //     } else {
        //         config ()->set ( 'auth.providers.users.model', User::class );
        //         config ()->set ( 'auth.providers.users.table', 'users' );
        //         config ()->set ( 'session.cookie', '_ls_ss' );
        //     }
        // }

        if (env("LS_HOST") == 1 && ($this->app['request']->header('host') === env("LS_TYPE_ADMIN")) === false) {
            // Log::infO("Model Customer 1");
            config()->set('auth.model', Customer::class);
            config()->set('auth.providers.users.table', 'customers');
            config()->set('session.cookie', '_ls_s');
        } else if (strpos($this->app['request']->path(), 'v1') !== false) {
            // Log::infO("Model Subscriber",);
            config()->set('auth.providers.users.model', OrgSubscribers::class);
            config()->set('auth.providers.users.table', 'org_subscribers');
            config()->set('session.cookie', '_ls_s');
        } else if (strpos($this->app['request']->path(), 'v2') !== false) {
            // Log::infO("Model Subscriber",);
            config()->set('auth.providers.users.model', OrgSubscribers::class);
            config()->set('auth.providers.users.table', 'org_subscribers');
            config()->set('session.cookie', '_ls_s');
        } else if (strpos($this->app['request']->path(), 'v3') !== false) {
            // Log::infO("Model Subscriber",);
            config()->set('auth.providers.users.model', OrgSubscribers::class);
            config()->set('auth.providers.users.table', 'org_subscribers');
            config()->set('session.cookie', '_ls_s');
        } else if (strpos($this->app['request']->path(), 'xtream') !== false) {
            // Log::infO("Model Subscriber",);
            config()->set('auth.providers.users.model', OrgSubscribers::class);
            config()->set('auth.providers.users.table', 'org_subscribers');
            config()->set('session.cookie', '_ls_s');
        } else if (strpos($this->app['request']->path(), 'admin') === false) {
            // Log::infO("Model Customer 2", [$this->app['request']->path()]);
            config()->set('auth.providers.users.model', Customer::class);
            config()->set('auth.providers.users.table', 'customers');
            config()->set('session.cookie', '_ls_s');
        } else {
            // Log::infO("Model User");
            config()->set('auth.providers.users.model', User::class);
            config()->set('auth.providers.users.table', 'users');
            config()->set('session.cookie', '_ls_ss');
        }


        $this->registerPolicies($gate);

        $gate->define('access', function ($user) {
            if ($user->hasAccess(Route::currentRouteAction())) {
                return true;
            }
            return false;
        });
    }
}
