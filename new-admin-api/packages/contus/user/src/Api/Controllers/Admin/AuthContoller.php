<?php

namespace Contus\User\Api\Controllers\Admin;

use Carbon\Carbon;
use Contus\Base\ApiController;
use Contus\User\Models\User;
use Contus\User\Repositories\AuthRepository;
use Contus\Base\Controller as BaseController;
use Contus\User\Traits\AuthendicateTrait;
use GeoIp2\Record\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
// use Illuminate\Support\Facades\Mail;
// use Illuminate\Support\Facades\Redirect;
use JWTAuth;
use Contus\Organizations\Model\Organization;

class AuthContoller extends ApiController
{
    use AuthendicateTrait;

    protected $authRepository;
    protected $authenticationConfig;
    protected $lockoutTime;
    protected $maxLoginAttempts;

    public function __construct(AuthRepository $authRepository)
    {
        $this->authRepository = $authRepository;
        $this->authenticationConfig = config('settings.security-settings.authentication', []);
        $this->lockoutTime = $this->authenticationConfig['lockout_time'] ?? 60;
        $this->maxLoginAttempts = $this->authenticationConfig['max_login_attempts'] ?? 5;
        config('auth.model', User::class);
        $this->middleware('guest', ['except' => ['logout']]);
    }

    /**
     * Handle login request
     */
    public function maxAttempts()
    {
        return $this->maxLoginAttempts;
    }

    public function decayMinutes()
    {
        return (int) $this->lockoutTime;
    }

    public function username()
    {
        return 'email';
    }

    /**
     * Handle login request
     */
    public function postLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
            'password' => 'required',
        ]);

        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            $seconds = $this->limiter()->availableIn($this->throttleKey($request));
            return $this->getErrorJsonResponse([], ['email' => 'Too many login attempts. Please try again in ' . ceil($seconds / 60) . ' minutes.'], 429);
        }

        if ($request->has('language')) {
            Session::put('site_language', $request->language);
        }

        // ✅ Get the user's IP address
        $ip = $request->ip();

        $credentials = $request->only('email', 'password');
        if ($token = JWTAuth::attempt($credentials)) {
            $this->clearLoginAttempts($request);

            if ($this->isUserInactive()) {
                return $this->getErrorJsonResponse([], ['email' => trans('user::adminuser.login_inactive')], 500);
            }

            $user = Auth::user();
            $user->ip_address = $ip;
            $user->is_log_in_at = Carbon::now();
            $user->save();

            if ($user->is_super_admin == 1) {
                $permissionObject = 'all';
                $organizations = Organization::where('owner_by', $user->id)->get();
            } else {
                $userPermissions = $user->rules()
                ->with(['permissions', 'organization'])
                ->first();

                $permissionObject = $userPermissions
                    ? $userPermissions->permissions->keyBy('permission_module_name')
                    : collect();

                $organizations = $userPermissions ? $userPermissions->organization : collect();
            }

            if ($organizations->isNotEmpty()) {
                $organizationsData = $organizations->map(function ($org) {
                    return [
                        'id' => $org->id,
                        'name' => $org->organization_name,
                    ];
                })->all();

                if ($permissionObject === 'all') {
                    $permissionObject = collect([
                        'all' => true,
                        // 'organization_id' => $organizations->first()->id,
                        'organizations' => $organizationsData,
                    ]);
                } else {
                    // $permissionObject->put('organization_id', $organizations->first()->id);
                    $permissionObject->put('organizations', $organizationsData);
                    // $permissionObject->put('permission_rule_id', $user->permission_rule_id);
                }
            }

            return $this->getSuccessJsonResponse([
                'data' => ['token' => $token, 'menu_permissions' => $permissionObject]
            ], 'authenticated');
        }

        $this->incrementLoginAttempts($request);
        return $this->handleFailedLogin($request);



        // if ($user->is_super_admin == 1) {
        //     $userPermissions = [
        //         (object) ['permissions' => 'all']
        //     ];
        // } else {
        // }

        // $userPermissions = User::with('group')
        //     ->where('id', Auth::id())
        //     ->get()
        //     ->pluck('group');
        // $userPermissions = json_decode($userPermissions);
    }



    // public function postLogin(Request $request) {
    //     $request->validate([
    //         'email' => 'required|email|max:255',
    //         'password' => 'required',
    //     ]);

    //     if ($request->has('language')) {
    //         Session::put('site_language', $request->language);
    //     }

    //     $credentials = $request->only('email', 'password');

    //     if ($token = JWTAuth::attempt($credentials)) {
    //         if ($this->isUserInactive()) {
    //             return $this->getErrorJsonResponse([], [
    //                 'email' => trans('user::adminuser.login_inactive')
    //             ], 500);
    //         }

    //         $userPermissions = [
    //             ['permissions' => ['dashboard_all']] // Example permissions, customize as needed
    //         ];

    //         return $this->getSuccessJsonResponse([
    //             'data' => [
    //                 'token' => $token,
    //                 'menu_permissions' => $userPermissions // ← sent as array, not string
    //             ]
    //         ], 'authenticated');
    //     }

    //     return $this->handleFailedLogin($request);
    // }


    /**
     * Check if user is inactive
     */

    public function logout(Request $request)
    {
        Auth::logout();
    }


    // $request->user()->currentAccessToken()->delete();
    // Invalidate the session and regenerate the CSRF token
    // $request->session()->invalidate();
    // $request->session()->regenerateToken();

    // return redirect('/');

    // return response()->json([
    //     'message' => 'Token revoked, user logged out.',
    // ]);


    protected function isUserInactive()
    {
        if (Auth::user()->is_active == 0) {
            Auth::logout();
            return true;
        }
        return false;
    }

    /**
     * Handle failed login attempt
     */
    protected function handleFailedLogin(Request $request)
    {
        $userInfo = User::where('email', $request->email)->first();
        $error = ['email' => trans('user::adminuser.email_not_registered')];

        if ($userInfo) {
            $error = ($userInfo->is_active != 1)
                ? ['email' => trans('user::adminuser.login_inactive')]
                : ['password' => trans('user::adminuser.invalid_password_details')];
        }

        return $this->getErrorJsonResponse([], $error, 422);
    }

    /**
     * Get validation rules
     */
    public function getInfo()
    {
        return $this->getSuccessJsonResponse([
            'info' => ['rules' => ['email' => 'required|email|max:255', 'password' => 'required']]
        ]);
    }

    /**
     * Get forgot password validation rules
     */
    public function getForgotPwdInfo()
    {
        return $this->getSuccessJsonResponse([
            'info' => ['rules' => ['email' => 'required|email']]
        ]);
    }

    /**
     * Handle forgot password
     */
    public function postForgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email|max:255']);

        $userInfo = User::where('email', $request->email)->first();
        if (!$userInfo) {
            return $this->getErrorJsonResponse([], ['email' => trans('user::adminuser.email_not_registered')], 500);
        }

        return $this->authRepository->resetAndUpdatePassword()
            ? $this->getSuccessJsonResponse(['data' => trans('user::auth.forgotpassword.success')], 200)
            : $this->getErrorJsonResponse([], ['email' => trans('user::auth.forgotpassword.not_registered')], 500);
    }
}
