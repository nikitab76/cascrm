<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class testcontroller extends Controller
{
    public function index()
    {
        $rooms = Room::all();
        foreach ($rooms as $room)
        {
            dump($room);
        }


    }
}
