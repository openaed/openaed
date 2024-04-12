<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function switch(Request $request, $lang)
    {
        if (!in_array($lang, array_keys(config('app.available_locales')))) {
            return redirect()->back();
        }
        $request->session()->put('language', $lang);
        return redirect()->back();
    }
}