<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('pages.index');
})->name('home');

Route::get('/docs', function () {
    return view('pages.apidocs');
})->name('docs');

Route::get('/aed-info', function () {
    return view('pages.aedinfo');
})->name('aedinfo');

Route::get('/about-us', function () {
    return view('pages.about-us');
})->name('about-us');

Route::get('/statistics', function () {

    $distinctCities = \App\Models\Defibrillator::select('city')->distinct()->where('city', '!=', null)->get()->count();

    $totalAEDs = \App\Models\Defibrillator::all()->count();
    $aedPerProvince = \App\Models\Defibrillator::select('province', \DB::raw('count(*) as total'))->groupBy('province')->where('province', '!=', null)->get();
    $aedPerCity = \App\Models\Defibrillator::select('city', \DB::raw('count(*) as total'), 'province')->groupBy('city', 'province')->where('city', '!=', null)->orderBy('total', 'desc')->get();
    $aedPerOperator = \App\Models\Defibrillator::select('operator', \DB::raw('count(*) as total'))->groupBy('operator')->where('operator', '!=', null)->orderBy('total', 'desc')->get();

    return view('pages.statistics', [
        'distinctCities' => $distinctCities,
        'totalAEDs' => $totalAEDs,
        'aedPerProvince' => $aedPerProvince,
        'aedPerCity' => $aedPerCity,
        'aedPerOperator' => $aedPerOperator
    ]);
})->name('statistics');

Route::get('/lang/{lang}', 'App\Http\Controllers\LanguageController@switch')->name('lang.switch');
