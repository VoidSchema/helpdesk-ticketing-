<?php

namespace App\Http\Middleware;

use App\Enums\Role;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        $userRole = $user->role;

        foreach ($roles as $role) {
            if ($userRole->value === $role) {
                return $next($request);
            }
        }

        abort(403, 'Unauthorized. Required role: ' . implode(' or ', $roles));
    }
}
