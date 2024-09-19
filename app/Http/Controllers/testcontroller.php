<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class testcontroller extends Controller
{
    public function index()
    {
        dd(Room::all());

    }
}
