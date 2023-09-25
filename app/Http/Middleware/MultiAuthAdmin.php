<?php

namespace App\Http\Middleware;

use Closure;

class MultiAuthAdmin
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
        if(auth()->user()->type==='superadmin'){
            return $next($request);
        }
          
        return response()->json(['You do not have permission to access for this page.']);
    }
}
