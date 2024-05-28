<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use App\Models\APIKey;
use App\Models\AccessLog;
use App\Models\BlacklistedIP;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAPIKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($this->isLocalhost()) // always allow localhost
            return $next($request);

        $referer = parse_url($request->headers->get('referer'))['host'] ?? "";
        $ip = $request->ip();
        $apiKey = $request->input('key');

        $log = new AccessLog();
        $log->ip = $ip;
        $log->route = $request->path();
        $log->method = $request->method();
        $log->api_key = $apiKey ?? null;
        $log->save();

        // Check if the IP is blacklisted
        $blacklistedIP = BlacklistedIP::where('ip', $ip)->first();

        if ($blacklistedIP !== null) {
            return response()->json(["message" => "Unauthorised"], 403);
        }

        $whitelistedDomains = config('app.whitelisted_domains');
        $whitelistedDomains = explode(";", $whitelistedDomains);

        if ($apiKey === null) {
            return response()->json(["message" => "No API key provided"], 401);
        }

        if ($apiKey != null && in_array($referer, $whitelistedDomains) == false) {
            // Check if the API key exists
            $dbKey = APIKey::where('key', $apiKey)->first();

            $now = Carbon::now();
            $now->setTimezone('Europe/Amsterdam');

            // If no record returned, bad key
            if ($dbKey === null || ($dbKey->valid_until < $now && $dbKey->valid_until !== null)) {
                return response()->json(["message" => "Invalid API key"], 401);
            }
        }

        return $next($request);
    }

    private function isLocalhost($whitelist = ['127.0.0.1', '::1'])
    {
        return in_array($_SERVER['REMOTE_ADDR'], $whitelist);
    }
}