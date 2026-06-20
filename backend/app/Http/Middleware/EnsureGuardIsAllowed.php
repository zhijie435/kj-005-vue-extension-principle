<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureGuardIsAllowed
{
    public function handle(Request $request, Closure $next, string $guard): Response
    {
        $user = $request->user();

        if (! $user || $user->guard_name !== $guard) {
            return response()->json([
                'code' => 1,
                'data' => null,
                'message' => '无权访问',
            ], 403);
        }

        return $next($request);
    }
}
