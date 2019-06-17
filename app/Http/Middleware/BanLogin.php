<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Illuminate\Support\Facades\Auth;

class BanLogin
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
        $user = User::where('email', $request->get('email'))->firstOrFail();
        if ($user->status) {
            return redirect()->back()->with('status', 'К сожаленью вы забанены :(');
        }
        return $next($request);
    }
}
