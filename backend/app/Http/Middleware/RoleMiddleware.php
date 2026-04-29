<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (! auth()->check()) {
            return redirect()->route('login');
        }

        $allowedRoles = collect($roles)
            ->flatMap(fn ($role) => preg_split('/[|,]/', $role))
            ->filter()
            ->values()
            ->all();

        if (! auth()->user()->hasAnyRole($allowedRoles)) {
            abort(403);
        }

        return $next($request);
    }
}
