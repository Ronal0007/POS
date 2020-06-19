<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class ManagerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (auth::check()){
            if (auth::user()->role->name =='Manager'){
                if (auth::user()->isActive==1){
                    return $next($request);
                }else{
                    return redirect('/suspended');
                }
            }
            elseif (auth::user()->role->name =='seller'){
                if (auth::user()->isActive==1){
                    return redirect('/main');
                }else{
                    return redirect('/suspended');
                }
            }
            else{
                return redirect('/');
            }
        }else{
            return redirect('/login');
        }


//        if(Auth::check()){
//            if(Auth::user()->isActive==1){  //check if user is active
//                if(Auth::user()->role->name='Manager'){
//                    return $next($request);
//                }else{
//                    return redirect('/main');
//                }
//            }else{
//                return redirect('/suspended');
//            }
//
//        }else{
//            return redirect('/login');
//        }
    }
}
