<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FreeFloating extends Controller
{
    public function index()
    {
        return view('freeFloating');
    }
}
