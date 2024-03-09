<?php

namespace App\Http\Controllers\API;

use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\AttributedResponse;
use App\Http\Controllers\Controller;

class ProvinceController extends Controller
{
    /**
     * Get all provinces
     *
     * @return JsonResponse
     */
    public function all(): JsonResponse
    {
        $provinces = Province::all()->each->makeHidden(['created_at', 'updated_at', 'deleted_at']);
        return response()->json(AttributedResponse::new($provinces));
    }
}