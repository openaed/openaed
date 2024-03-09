<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\AttributedResponse;
use App\Models\Defibrillator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
        $defibrillators = Defibrillator::where('province', $province)->get()->each->makeHidden(['created_at', 'updated_at', 'deleted_at']);
        return response()->json(AttributedResponse::new($defibrillators));
    }

    /**
     * Get all defibrillators
     *
     * @return JsonResponse
     */
    public function all(): JsonResponse
    {
        $defibrillators = Defibrillator::all()->each->makeHidden(['created_at', 'updated_at', 'deleted_at']);
        return response()->json(AttributedResponse::new($defibrillators));
    }

    public static function import()
    {
        $overpass = "https://overpass-api.de/api/interpreter?data=%5Bout%3Ajson%5D%5Btimeout%3A25%5D%3B%0Aarea%28id%3A3600047796%29-%3E.searchArea%3B%0Anwr%5B%22emergency%22%3D%22defibrillator%22%5D%28area.searchArea%29%3B%0Aout%20geom%3B";

        $baseNominatim = "https://nominatim.openstreetmap.org/lookup?osm_ids=N";

        $defibrillators = json_decode(file_get_contents($overpass), true);

        foreach ($defibrillators['elements'] as $defibrillator) {
            if (Defibrillator::where('osm_id', $defibrillator['id'])->exists()) {
                $defibModel = Defibrillator::where('osm_id', $defibrillator['id'])->first();
            } else {
                $defibModel = new Defibrillator();
            }

            $defibModel->osm_id = $defibrillator['id'];
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
                } else {
                    $defibModel->city = null;
                }
                $defibModel->province = $nominatim['address']['state'] ?? null;
            }

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
            $defibModel->note = $defibrillator['tags']['note'] ?? null;
            $defibModel->save();

            if ($defibModel->city != null) {
                sleep(1);
            }
        }
    }
}