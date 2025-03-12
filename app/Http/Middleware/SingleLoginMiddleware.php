<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;


class SingleLoginMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        if ($user && $user->session_id !== session('session_id')) {
            User::where('id', Auth::id())->update(['token' => null]);
            Auth::logout();
            return redirect()->route('login')->with('error', 'Your session has expired or you are logged in elsewhere.');
        }

        return $next($request);
    }
}
