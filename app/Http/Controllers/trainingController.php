<?php

namespace App\Http\Controllers;

use App\Models\Training;
use Illuminate\Http\Request;

class trainingController extends Controller
{
    public function create(Request $request){
        //dd($request);
        Training::create([
            'slug_room' => $request->roomsSlug,
            'coach' => $request->classCoach,
            'profile' => $request->classProfile,
            'date' => $request->classDate,
            'time' => $request->classTime,
            'quarter' => $request->classQuarter,
            'comment' => $request->classComment
        ]);
        return redirect()->route('rooms.show', ['room'=>$request->roomsSlug]);
    }
}
