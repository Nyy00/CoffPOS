<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        
        // Check if user has any of the required roles
        if (!empty($roles)) {
            $hasRole = false;
            foreach ($roles as $role) {
                if ($user->role === $role) {
                    $hasRole = true;
                    break;
                }
            }
            
            if (!$hasRole) {
                abort(403, 'Unauthorized access. Required role: ' . implode(' or ', $roles));
            }
        }

        // Log admin access for security
        if ($user->isAdmin()) {
            \Log::info('Admin access', [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'route' => $request->route()->getName(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);
        }

        return $next($request);
    }
}