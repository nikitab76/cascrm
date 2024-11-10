<?php

namespace App\Http\Controllers;

use App\Models\Job_title;
use App\Models\Room;
use App\Models\Training;
use Illuminate\Http\Request;

class testcontroller extends Controller
{
    public function index()
    {
        $d = Job_title::getRole('Занимающийся');
        dd($d);
        $t = new RoomsController();
        dump($t->showTable());
    }
}
