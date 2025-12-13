<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class changeLanguageController extends Controller
{
     public function changeLanguage(Request $request)
{
    $lang = $request->input('lang');

    if (! in_array($lang, ['ar', 'en'])) {
        return response()->json([
            'message' => 'Unsupported language',
        ], 400);
    }

    App::setLocale($lang);

    return response()->json([
        'message' => 'Language changed successfully',
        'locale' => App::getLocale(),
    ]);
}
}
