<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if ($user === null) {
            throw new AuthorizationException('You must be authenticated to perform this action.');
        }

        if (! in_array($user->role?->value ?? $user->role, $roles, true)) {
            throw new AuthorizationException('You do not have permission to perform this action.');
        }

        return $next($request);
    }
}
