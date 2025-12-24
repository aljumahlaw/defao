<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForcePasswordReset
{
    public function handle(Request $request, Closure $next): Response
    {
        if (
            auth()->check()
            && ! auth()->user()->isAdmin()
            && is_null(auth()->user()->password_changed_at)
            && ! (
                $request->routeIs('profile.*')
                || $request->routeIs('password.*')
                || $request->routeIs('logout')
            )
        ) {
            return redirect()
                ->route('profile.settings')
                ->with('warning', 'يرجى تغيير كلمة المرور أولاً');
        }

        return $next($request);
    }
}
