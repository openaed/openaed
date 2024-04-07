<?php

namespace App\Http\Controllers\API;

use App\Models\Synchronisation;
use Carbon\Carbon;
use App\Helpers\Discord;
use App\Models\Province;
use Illuminate\Http\Request;
use App\Models\Defibrillator;
use Illuminate\Http\JsonResponse;
use App\Models\AttributedResponse;
use App\Http\Controllers\Controller;

class DefibrillatorController extends Controller
{
    /**
     * Get all defibrillators in a city
     *
     * @param string $province The province to search in
     * @param string $city The city to search in
     * @return JsonResponse
     */
    public function getByCity($province, $city): JsonResponse
    {
        $defibrillators = Defibrillator::where('city', $city)->where('province', $province)->get()->each->makeHidden(['created_at', 'updated_at', 'deleted_at']);
        $provinceObj = Province::where('name', $province)->first();
        // If province does not exist, return 400 (Bad Request)
        if ($provinceObj == null) {
            return response()->json(["message" => "Invalid province", "options" => Province::all()->pluck('name')], 400);
        }
        return response()->json(AttributedResponse::new($defibrillators));
    }

    /**
     * Get all defibrillators in a province
     *
     * @param string $province The province to search in
     * @return JsonResponse
     */
    public function getByProvince($province): JsonResponse
    {
        $provinceObj = Province::where('name', $province)->first();
        // If province does not exist, return 400 (Bad Request)
        if ($provinceObj == null) {
            return response()->json(["message" => "Invalid province", "options" => Province::all()->pluck('name')], 400);
        }
        $defibrillators = Defibrillator::where('province', $province)->get()->each->makeHidden(['created_at', 'updated_at', 'deleted_at']);
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
            // We've imported ALL AEDs now - their updated_at values have changed.
            // Any AED whose updated_at value is not at or after $now has been removed from OSM.
            // We can now safely delete these AEDs from our database.
            // This uses soft deletes, so the data is not lost.

            $toDelete = Defibrillator::where('updated_at', '<', $now)->get();

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
        $lastUpdate = Carbon::parse(Defibrillator::max('updated_at'));

        if ($lastUpdate && !$all) { // if $all is true, get all defibrillators
            $year = $lastUpdate->year;
            $month = $lastUpdate->format('m');
            $day = $lastUpdate->format('d');

            $time = "%28newer%3A%22{$year}-{$month}-{$day}T00%3A00%3A00Z%22%29";
        } else { // Fresh database - no last update yet
            $time = "";
        }

        Discord::syncStarted($all);
        $sync = Synchronisation::create(['start' => $start, 'status' => 'requesting', 'full' => $all]);

        try {
            $overpass = "https://overpass-api.de/api/interpreter?data=%5Bout%3Ajson%5D%5Btimeout%3A25%5D%3B%0Aarea%28id%3A3600047796%29-%3E.searchArea%3B%0Anode%5B%22emergency%22%3D%22defibrillator%22%5D%28area.searchArea%29{$time}%3B%0Aout%20geom%3B%0A";

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

                    $nominatim = json_decode(file_get_contents($baseNominatim . $defibrillator['id'] . "&format=json", false, $context), true)[0];
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
                        $defibModel->province = $nominatim['address']['state'];
                    } else if (isset($nominatim['address']['municipality'])) { // Fallback for Caribbean Netherlands (Bonaire, Sint Eustatius, Saba)
                        $defibModel->province = $nominatim['address']['municipality'];
                    } else {
                        $defibModel->province = null;
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
}