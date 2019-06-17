<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class BanRequests
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return Closure | string
     */
    public function handle($request, Closure $next)
    {
        if (Auth::user()->status) {
            Auth::logout();
            return redirect()->back()->with('status', 'К сожаленью вы забанены :(');
        }
        return $next($request);
    }
}
