<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard=null)
    {
        if ($request->user && $request->user()->permission < config('constants.ADMIN') ) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
