<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;

class IsPublisher
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
         if(!empty(Auth::user())){
            if(Auth::user()->user_role == '2')
            {
                session(['user_id' => Auth::user()->id,'user_role' => Auth::user()->user_role]);
                return $next($request);
            }else{
                return redirect()->back();
            }
            return $next($request);
         }else{
            return redirect('/');
         }
    }
}
