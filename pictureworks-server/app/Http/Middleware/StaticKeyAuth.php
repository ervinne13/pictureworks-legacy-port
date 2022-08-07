<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Pictureworks\AppKeyValidator;

class StaticKeyAuth
{
    public function __construct(
        protected AppKeyValidator $appKeyValidator
    ) {
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Let's reuse the password for now due to the existing implementation.
        // Research if this is really okay though most say just putting it
        // in a bearer token doesn't really make any difference unless we generate
        // the token normally like we do with an authentication route.
        $key = $request->password;

        if ($this->appKeyValidator->validate($key)) {
            return $next($request);
        }

        // Legacy implementation
        return response('Invalid password', 401);
    }
}
