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
}