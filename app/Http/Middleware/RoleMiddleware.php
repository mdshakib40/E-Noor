<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(
        Request $request,
        Closure $next,
        ...$roles
    ): Response {
        $user = $request->user();

        if (!$user) {
            abort(403);
        }

        foreach ($roles as $role) {

            try {
                $allowedRole = UserRole::from($role);
            } catch (\ValueError $e) {
                continue;
            }

            if ($user->role === $allowedRole) {
                return $next($request);
            }
        }

        abort(403);
    }
}

