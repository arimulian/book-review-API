<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ApiAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header('Authorization');
        if (!$token) {
            return response()->json([
               'message' => 'Unauthorized',
            ], 401);
        }
        $user = User::query()->where('token', $token)->first();
        if (!$user) {
            return response()->json([
               'message' => 'Unauthorized',
            ], 401);
        }
        Auth::login($user);
        return $next($request);
    }
}
