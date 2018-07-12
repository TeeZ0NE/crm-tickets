<?php

namespace App\Http\Middleware;

use Closure;
use Purify;

class PurifyRequest
{
    /**
     * Handle an incoming request.
     * sanitize request
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
		if(isset($request->name)){
        	$request->name = Purify::clean($request->name);
		}
        return $next($request);
    }
}
