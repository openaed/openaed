<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Localhost
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$this->isLocalhost()) // only allow localhost
            return response()->json(["message" => "Unauthorised"], 401);

        return $next($request);
    }

    private function isLocalhost($whitelist = ['127.0.0.1', '::1'])
    {
        array_push($whitelist, $_SERVER['SERVER_ADDR']);
        return in_array($_SERVER['REMOTE_ADDR'], $whitelist);
    }
}
