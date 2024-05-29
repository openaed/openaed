<?php

namespace App\Http\Controllers\API;

use Carbon\Carbon;
use App\Helpers\Git;
use App\Helpers\Discord;
use App\Models\Province;
use App\Models\AccessLog;
use Illuminate\Http\Request;
use App\Models\Defibrillator;
use App\Models\Synchronisation;
use Illuminate\Http\JsonResponse;
use App\Models\AttributedResponse;
use App\Http\Controllers\Controller;

class DefibrillatorController extends Controller
{
    /**
     * Get all defibrillators in a city
     *
     * @param string $region The region to search in
     * @param string $city The city to search in
     * @return JsonResponse
     */
    public function getByCity($region, $city): JsonResponse
    {
        $defibrillators = Defibrillator::where('city', $city)->where('region', $region)->get()->each->makeHidden(['created_at', 'updated_at', 'deleted_at']);
        $regionObj = Province::where('name', $region)->first();
        // If province does not exist, return 400 (Bad Request)
        if ($regionObj == null) {
            return response()->json(["message" => "Invalid province", "options" => Province::all()->pluck('name')], 400);
        }
        return response()->json(AttributedResponse::new($defibrillators));
    }

    /**
     * Get all defibrillators in a region
     *
     * @param string $region The region to search in
     * @return JsonResponse
     */
    public function getByRegion($region): JsonResponse
    {
        $regionObj = Province::where('name', $region)->first();
        // If province does not exist, return 400 (Bad Request)
        if ($regionObj == null) {
            return response()->json(["message" => "Invalid region", "options" => Province::all()->pluck('name')], 400);
        }
        $defibrillators = Defibrillator::where('region', $region)->get()->each->makeHidden(['created_at', 'updated_at', 'deleted_at']);
        return response()->json(AttributedResponse::new($defibrillators));
    }

    /**
     * Get all defibrillators
     *
     * @return JsonResponse
     */
    public function all(Request $request): JsonResponse
    {
        $defibrillators = Defibrillator::all()->each->makeHidden(['created_at', 'updated_at', 'deleted_at']);
        return response()->json(AttributedResponse::new($defibrillators));
    }

    /**
     * Get one defibrillator by ID
     *
     * @return JsonResponse
     */
    public function getById(Request $request, $id): JsonResponse
    {
        $defibrillator = Defibrillator::find($id);
        return response()->json(AttributedResponse::new($defibrillator));
    }

    /**
     * Get basic defibrillator information
     *
     * @return JsonResponse
     */
    public function basic(Request $request): JsonResponse
    {
        $defibrillators = Defibrillator::select('id', 'latitude', 'longitude', 'access')->get()->toArray();
        return response()->json(AttributedResponse::new($defibrillators));
    }

    /**
     * Cleanup the defibrillator database
     *
     * This function will remove all defibrillators from the database that have been removed from OpenStreetMap.
     * This function is called by a scheduled task.
     *
     * @return void
     */
    public static function cleanup(): void
    {
        $now = Carbon::now();

        DefibrillatorController::import(true, function ($new) use ($now) {
            // We've imported ALL AEDs now - their synced_at values have changed.
            // Any AED whose synced_at value is not at or after $now has been removed from OSM.
            // We can now safely delete these AEDs from our database.
            // This uses soft deletes, so the data is not lost.

            $toDelete = Defibrillator::where('synced_at', '<', $now)->get();

            foreach ($toDelete as $defibrillator) {
                $defibrillator->delete();
            }

            Discord::cleanedUp($toDelete->count()); // Notify Discord
        });
    }

    /**
     * Import defibrillators from OpenStreetMap
     *
     * @param bool $all Whether to import all defibrillators
     * @return int The amount of defibrillators imported
     */
    public static function import($all = false, $callback = null): int
    {

        $start = Carbon::now();

        // Get max updated_at from Defibrillator
        $lastSyncedAt = Defibrillator::max('synced_at');
        $lastSync = Carbon::parse($lastSyncedAt);

        if ($lastSyncedAt && !$all) { // if $all is true, get all defibrillators
            $year = $lastSync->year;
            $month = $lastSync->format('m');
            $day = $lastSync->format('d');

            $time = "%28newer%3A%22{$year}-{$month}-{$day}T00%3A00%3A00Z%22%29";
        } else { // Fresh database - no last update yet
            $time = "";
            $all = true;
        }

        Discord::syncStarted($all);
        $sync = Synchronisation::create(['start' => $start, 'status' => 'requesting', 'full' => $all]);

        try {
            $region = config('app.region') ?? 3600047796; // Get region from config (default: 3600058193 - The Netherlands)
            // if $region contains a ;
            if (strpos($region, ';') !== false) {
                $region = explode(';', $region);
                $region = implode(',', $region);
            }
            $overpass = "https://overpass-api.de/api/interpreter?data=%5Bout%3Ajson%5D%5Btimeout%3A25%5D%3B%0Aarea%28id%3A{$region}%29-%3E.searchArea%3B%0Anode%5B%22emergency%22%3D%22defibrillator%22%5D%28area.searchArea%29{$time}%3B%0Aout%20geom%3B%0A";

            $baseNominatim = "https://nominatim.openstreetmap.org/lookup?osm_ids=N";

            $defibrillators = json_decode(file_get_contents($overpass), true);

            $sync->update(['status' => 'processing']);

            $new = 0;

            foreach ($defibrillators['elements'] as $defibrillator) {
                $defibModel = Defibrillator::updateOrCreate(
                    ['osm_id' => $defibrillator['id']],
                    [
                        'osm_id' => $defibrillator['id'],
                        'latitude' => $defibrillator['lat'],
                        'longitude' => $defibrillator['lon']
                    ]
                );

                if ($defibModel->city == null) { // Only ask Nominatim for the city if it's not already set
                    $options = array(
                        'http' => array(
                            'method' => "GET",
                            'header' => "Accept-language: nl\r\n" .
                                "Cookie: foo=bar\r\n" .
                                "User-Agent: OpenAED - Laravel application\r\n"
                        )
                    );

                    $context = stream_context_create($options);

                    $nominatim = json_decode(file_get_contents($baseNominatim . $defibrillator['id'] . "&format=json&accept_language" . config('app.locale'), false, $context), true)[0];
                    if (isset($nominatim['address']['city'])) {
                        $defibModel->city = $nominatim['address']['city'] ?? null;
                    } else if (isset($nominatim['address']['town'])) {
                        $defibModel->city = $nominatim['address']['town'] ?? null;
                    } else if (isset($nominatim['address']['village'])) {
                        $defibModel->city = $nominatim['address']['village'] ?? null;
                    } else if (isset($nominatim['address']['municipality'])) { // Some AEDs are not in a place
                        $defibModel->city = $nominatim['address']['municipality'] ?? null;
                    } else {
                        $defibModel->city = null;
                    }

                    if (isset($nominatim['address']['state'])) {
                        $defibModel->region = $nominatim['address']['state'];
                    } else if (isset($nominatim['address']['municipality'])) { // Fallback for Caribbean Netherlands (Bonaire, Sint Eustatius, Saba)
                        $defibModel->region = $nominatim['address']['municipality'];
                    } else {
                        $defibModel->region = null;
                    }

                }

                $defibModel->osm_id = $defibrillator['id'];
                $defibModel->latitude = $defibrillator['lat'];
                $defibModel->longitude = $defibrillator['lon'];
                $defibModel->access = $defibrillator['tags']['access'] ?? null;
                if (isset($defibrillator['tags']['indoor'])) {
                    $defibModel->indoor = $defibrillator['tags']['indoor'] == "yes" ? 1 : 0;
                } else {
                    $defibModel->indoor = null;
                }
                $defibModel->operator = $defibrillator['tags']['operator'] ?? null;
                $defibModel->operator_website = $defibrillator['tags']['operator:website'] ?? null;
                if (isset($defibrillator['tags']['phone:NL'])) {
                    $defibModel->phone = $defibrillator['tags']['phone:NL'];
                } else if (isset($defibrillator['tags']['operator:phone'])) {
                    $defibModel->phone = $defibrillator['tags']['operator:phone'];
                } else {
                    $defibModel->phone = $defibrillator['tags']['phone'] ?? null;
                }
                if (isset($defibrillator['tags']['defibrillator:location:nl'])) {
                    $defibModel->location = $defibrillator['tags']['defibrillator:location:nl'];
                } else {
                    $defibModel->location = $defibrillator['tags']['defibrillator:location'] ?? null;
                }
                $defibModel->opening_hours = $defibrillator['tags']['opening_hours'] ?? null;
                $defibModel->manufacturer = $defibrillator['tags']['manufacturer'] ?? null;
                $defibModel->model = $defibrillator['tags']['model'] ?? null;
                $defibModel->level = $defibrillator['tags']['level'] ?? null;
                $defibModel->image = $defibrillator['tags']['image'] ?? null;
                $defibModel->cabinet = $defibrillator['tags']['defibrillator:cabinet'] ?? null;
                $defibModel->cabinet_manufacturer = $defibrillator['tags']['defibrillator:cabinet:manufacturer'] ?? null;
                $defibModel->description = $defibrillator['tags']['description'] ?? null;
                $defibModel->synced_at = Carbon::now();
                $defibModel->save();

                if ($defibModel->city != null) {
                    sleep(1);
                }

                $new++;
            }

            Discord::syncFinished(Defibrillator::count());
            $sync->update(['status' => 'done', 'end' => Carbon::now(), 'modified' => $new]);
            $sync->save();

            if ($callback)
                $callback($new);

            return $new;
        } catch (\Exception $e) {
            $sync->update(['status' => 'error', 'end' => Carbon::now()]);
            $sync->save();

            Discord::error($e->getMessage());
            return 0;
        }
    }

    public function stats()
    {
        $total = Defibrillator::count();
        $cities = Defibrillator::select('city')->distinct()->get()->count();
        $uniqueVisitorsToday = AccessLog::whereDate('created_at', Carbon::today())->distinct('ip')->count();
        $uniqueVisitorsSevenDays = AccessLog::whereDate('created_at', '>=', Carbon::today()->subDays(7))->distinct('ip')->count();
        $uniqueVisitorsTotal = AccessLog::distinct('ip')->count();

        return response()->json([
            'defibrillators' => $total,
            'cities' => $cities,
            'git' => Git::getGitInfo(),
            'unique_visitors' => [
                'today' => $uniqueVisitorsToday,
                'seven_days' => $uniqueVisitorsSevenDays,
                'total' => $uniqueVisitorsTotal
            ]
        ]);
    }

    public function geojson()
    {
        $defibrillators = Defibrillator::all();
        $geojson = [
            'type' => 'FeatureCollection',
            'features' => []
        ];

        foreach ($defibrillators as $defibrillator) {
            $geojson['features'][] = [
                'type' => 'Feature',
                'geometry' => [
                    'type' => 'Point',
                    'coordinates' => [
                        $defibrillator->longitude,
                        $defibrillator->latitude
                    ]
                ],
                'properties' => [
                    'id' => $defibrillator->id,
                    'access' => $defibrillator->access,
                ]
            ];
        }

        return response()->json($geojson);
    }
}