<?php
namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class UpdateLastActive
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $user->last_active_at = Carbon::now();
            $user->save();
        }

        return $next($request);
    }
}

