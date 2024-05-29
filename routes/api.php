<?php

use Illuminate\Http\Request;
use App\Models\Defibrillator;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ProvinceController;
use App\Http\Controllers\API\DefibrillatorController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['prefix' => 'v1', 'middleware' => ['localhost']], (function () {

    Route::get('/stats', [DefibrillatorController::class, 'stats'])->name('api.stats');

}));

Route::group(['prefix' => 'v1', 'middleware' => ['apikey']], (function () {

    Route::get('/', function () {
        return [
            "message" => "Welcome to the OpenAED API",
            "endpoints" => [
                "GET /v1/api/regions" => "Get all regions",
                "GET /v1/api/all" => "Get all AEDs",
                "GET /v1/api/basic" => "Get all AEDs with basic information - ID, coordinates, access",
                "GET /v1/api/aed/{id}" => "Get a specific AED",
                "GET /v1/api/{region}" => "Get all AEDs in a specific region",
                "GET /v1/api/{region}/{city}" => "Get all AEDs in a specific city",
            ],
        ];
    })->name('api.index');

    Route::get('regions', [ProvinceController::class, 'all'])->name('api.regions');

    Route::get('all', [DefibrillatorController::class, 'all'])->name('api.aed.all');

    Route::get('basic', [DefibrillatorController::class, 'basic'])->name('api.aed.basic');

    Route::get('geojson', [DefibrillatorController::class, 'geojson'])->name('api.aed.geojson');

    Route::get('aed/{id}', [DefibrillatorController::class, 'getById'])->name('api.aed.one');

    Route::get('{region}/{city}', [DefibrillatorController::class, 'getByCity'])->name('api.aed.city');

    Route::get('{region}', [DefibrillatorController::class, 'getByRegion'])->name('api.aed.region');

}));