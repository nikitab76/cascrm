<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Training;
use Illuminate\Http\Request;

class testcontroller extends Controller
{
    public function index()
    {
        $q = Training::where('slug_room', 'bolshoy_igrovoy_zal')->first();
        dump($q);
        exit;
        $rooms = Room::all();
        foreach ($rooms as $room)
        {
            dump($room);
        }


    }
}
