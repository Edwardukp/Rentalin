<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $role  'owner' or 'tenant'
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if ($role === 'owner' && !$user->isOwner()) {
            abort(403, 'Access denied. Owner role required.');
        }

        if ($role === 'tenant' && !$user->isTenant()) {
            abort(403, 'Access denied. Tenant role required.');
        }

        return $next($request);
    }
}
