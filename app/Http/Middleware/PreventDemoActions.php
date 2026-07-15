<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\Response;

class PreventDemoActions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if (config('app.demo')) {
            if (
                in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE']) 
                && !$request->route()->named('admin.login') 
                && !$request->route()->named('admin.logout')
            ) {
                if ($request->expectsJson() || $request->header('X-Inertia')) {
                    return Redirect::back()->with('error', 'This action is disabled in demo mode.');
                }
                abort(403, 'This action is disabled in demo mode.');
            }
        }

        return $next($request);
    }
}
