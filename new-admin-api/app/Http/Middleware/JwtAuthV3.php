<?php

namespace App\Http\Middleware;

/*use Closure;
use JWTAuth;
use Exception;*/

use App;
use Closure;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtAuthV3
{
    protected $auth;
    protected $user_id = null;

    public function __construct(JWTAuth $jwtAuth)
    {
        $this->auth = $jwtAuth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $nexttoken
     * @return mixed
     */
    public function handle($request, Closure $next, $novalidate = false)
    {
        $method = $request->method();
        if ((!$token = JWTAuth::setRequest($request)->getToken()) && !$novalidate) {
            return response()->json(['error' => true, 'message' => 'Token Not provided', 'method' => $method, 'statusCode' => 401], 401);
        } else {
            try {
                $user = Auth::guard('v3-api')->user();
                if ($user) {
                    Auth::shouldUse('v3-api');
                }
            } catch (\Exception $e) {
                return response()->json(['error' => true, 'message' => 'Token expired, login to continue', 'method' => $method, 'statusCode' => 401], 401);
            } catch (JWTException $e) {
                return response()->json(['error' => 'Invalid token', 'method' => $method, 'statusCode' => 401], 401);
            }
            if (!$user) {
                return response()->json(['error' => true, 'message' => 'User not found', 'statusCode' => 401], 401);
            }
        }
        $request['user_id'] = $user->id;
        return $next($request);
    }
}
