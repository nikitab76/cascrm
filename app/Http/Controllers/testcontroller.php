<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Training;
use Illuminate\Http\Request;

class testcontroller extends Controller
{
    public function index()
    {
        $t = new RoomsController();
        dump($t->showTable());
    }
}
