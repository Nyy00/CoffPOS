<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ManagerAccessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        
        // Allow admin full access
        if ($user->isAdmin()) {
            return $next($request);
        }
        
        // Manager restrictions
        if ($user->isManager()) {
            $route = $request->route();
            $routeName = $route->getName();
            $method = $request->method();
            
            // Restricted actions for managers
            $restrictedActions = [
                // Products - can view and manage but not bulk operations
                'admin.products.bulk-delete',
                'admin.products.bulk-update-stock',
                
                // Customers - view only
                'admin.customers.create',
                'admin.customers.store',
                'admin.customers.edit', 
                'admin.customers.update',
                'admin.customers.destroy',
                'admin.customers.bulk-delete',
                
                // Transactions - view only, no void/delete
                'admin.transactions.void',
                'admin.transactions.destroy',
                
                // Users - completely restricted
                'admin.users.*'
            ];
            
            // Check if current route is restricted
            foreach ($restrictedActions as $restrictedRoute) {
                if (str_contains($restrictedRoute, '*')) {
                    $pattern = str_replace('*', '', $restrictedRoute);
                    if (str_starts_with($routeName, $pattern)) {
                        abort(403, 'Access denied. Manager role does not have permission for this action.');
                    }
                } elseif ($routeName === $restrictedRoute) {
                    abort(403, 'Access denied. Manager role does not have permission for this action.');
                }
            }
            
            // Additional method-based restrictions
            if ($method === 'POST' || $method === 'PUT' || $method === 'PATCH' || $method === 'DELETE') {
                $readOnlyRoutes = [
                    'admin.customers', 
                    'admin.transactions'
                ];
                
                foreach ($readOnlyRoutes as $readOnlyRoute) {
                    if (str_starts_with($routeName, $readOnlyRoute) && !str_contains($routeName, 'expenses')) {
                        // Allow expenses modifications for managers
                        if (!str_contains($routeName, 'expenses')) {
                            abort(403, 'Access denied. Manager role has read-only access to this resource.');
                        }
                    }
                }
            }
        }
        
        return $next($request);
    }
}