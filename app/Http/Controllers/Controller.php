<?php

namespace App\Http\Controllers;

abstract class Controller
{
    public function authorize($ability)
    {
        if (auth()->user()->role === 'user') {
            abort(403);
        }
    }
}
