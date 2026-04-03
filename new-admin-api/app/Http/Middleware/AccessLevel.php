<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Gate;
use Illuminate\Contracts\Auth\Guard;
use Route;
class AccessLevel
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
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
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
        $response = $next($request);
       
        if (Gate::denies('access', $this->auth)) { 
          
            if(app('request')->header ( 'X-WEB-SERVICE' ) == 'true'){
                $request->session()->flash(
                    "error","You are not authorized to perform this action. Please contact your admin."
                );

                $response = response()->json([
                    "message" => "You are not authorized to perform this action. Please contact your admin.",
                    "accessFailure" => 1,
                    "redirectTo" => url('admin/dashboard/index?permission=1')
                ],403);
            } else {
                 /**   
                  * Here $ previous used to redirect to previous page when permission denied 
                  */
                $previous = url()->previous();
               
                if($previous)
                {   
                     /**
                      * Once again we are checking whether the particular user have access to dashboard otherwise we redirect to profile
                      */
                    if(check_menu_flag('dashboard_all'))
                    {
                    $response = redirect($previous)
                    ->withErrors ( "You are not authorized to perform this action. Please contact your admin." );  
                    }else{
                        $response = redirect('admin/users/profile')
                        ->withErrors ( "You are not authorized to perform this action. Please contact your admin." ); 
                    }
                }
                else
                {
                    if(check_menu_flag('dashboard_all'))
                    {
                        $response = redirect('admin/dashboard')
                            ->withErrors ( "You are not authorized to perform this action. Please contact your admin." );
                    }
                    else{
                        $response = redirect('admin/users/profile')
                                ->withErrors ( "You are not authorized to perform this action. Please contact your admin." ); 
                    }
                     
                }
                  
            }
        }
        else{
                /**
                 * active status of current logged user is taken,whether if user was inactive,then user automatically redirect to login
                 *  
                 *  $activeStatus = 1,user is active,$activeStatus =0,user is inactive 
                 */
                $activeStatus = $this->auth->user()->is_active;
                if(intval($activeStatus)===1)
                {  
                    /**
                     * This part is to deny permission for same admin user to edit his roles
                     */
                    $restrictedRoutes=array(URL("admin/groups/edit/{$this->auth->user()->group->id}"));
                
                    $route = url()->current();
            
                    if (in_array($route, $restrictedRoutes))
                    {
                        $previous = url()->previous();
                        if($previous)
                        {
                            $response = redirect()->back()
                            ->withErrors ( "You are not authorized to perform this action. Please contact your admin." );  

                        }
                        else
                        {
                            if(check_menu_flag('dashboard_all'))
                            {
                                $response = redirect('admin/dashboard')
                                    ->withErrors ( "You are not authorized to perform this action. Please contact your admin." );
                            }
                            else{
                                $response = redirect('admin/users/profile')
                                        ->withErrors ( "You are not authorized to perform this action. Please contact your admin." ); 
                            }
                        }
                    }
                }else{
                    
                    $response = $request->session()->flush();
                    $response = redirect('/admin')
                    ->withErrors ( "Your account was inactive. Please contact your admin." );  

                }
          
             }
        return $response;
    }
}