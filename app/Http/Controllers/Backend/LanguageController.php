<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function change(Request $request, string $language)
    {
        if(auth()->user()){
            auth()->user()->update([
                'language' => $language,
            ]);
        }
        language($language);

        return back();
    }
}
