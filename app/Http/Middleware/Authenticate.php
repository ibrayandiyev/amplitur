<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Str;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string[]  ...$guards
     * @return mixed
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function handle($request, Closure $next, ...$guards)
    {
        if (Str::startsWith($request->route()->getName(), 'backend')) {
            $guards = ['users', 'providers'];
        } else {
            $guards = ['clients'];
        }

        $this->authenticate($request, $guards);

        return $next($request);
    }
    
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            if (Str::startsWith($request->route()->getName(), 'backend')) {
                return route('backend.login');
            } else {
                return route('frontend.auth.login');
            }
        }
    }
}
