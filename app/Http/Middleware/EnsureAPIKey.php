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
        $referer = parse_url($request->headers->get('referer'))['host'] ?? "";
        $ip = $request->ip();
        $apiKey = $request->input('key');

        if ($apiKey != null) {
            $log = new AccessLog();
            $log->ip = $ip;
            $log->route = $request->path();
            $log->method = $request->method();
            $log->api_key = $apiKey;
            $log->save();
        }

        // Check if the IP is blacklisted
        $blacklistedIP = BlacklistedIP::where('ip', $ip)->first();

        if ($blacklistedIP !== null) {
            return response('Forbidden', 403);
        }

        if ($apiKey === null && !in_array($referer, ["dev.openaed.nl", "openaed.nl", "openaed.test"])) {
            return response('Unauthorized', 401);
        }

        if ($apiKey != null && !in_array($referer, ["openaed.nl", "openaed.test"])) {
            // Check if the API key exists
            $dbKey = APIKey::where('key', $apiKey)->first();

            // If no record returned, bad key
            $now = Carbon::now();
            $now->setTimezone('Europe/Amsterdam');

            if ($dbKey === null || ($dbKey->valid_until < $now && $dbKey->valid_until !== null)) {
                return response('Unauthorized', 401);
            }
        }

        return $next($request);
    }
}