<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureEnterpriseIsApproved
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user || $user->role !== 'seller' || !$user->enterprise) {
            return $next($request);
        }

        if ($user->enterprise->status !== 'approved') {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Your store is currently under review.'], 403);
            }
            return response()->view('seller.pending-review', [], 403);
        }

        return $next($request);
    }
}
