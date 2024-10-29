<?php

namespace App\Http\Controllers;

use App\Models\Training;
use Illuminate\Http\Request;

class trainingController extends Controller
{
    public function create(Request $request){
        //dd($request);
        $request->validate([
            'coach' => 'required',
            'profile' => 'required',
            'date' => 'required',
            'time_start' => 'required',
            'time_end' => 'required',
        ]);
        /*Training::create([
            'slug_room' => $request->roomsSlug,
            'coach' => $request->classCoach,
            'profile' => $request->classProfile,
            'date' => $request->classDate,
            'time_start' => $request->classTime,
            'time_end' => $request->classTimeEnd,
            'quarter' => $request->classQuarter,
            'comment' => $request->classComment ? $request->classComment : '-',
        ]);
        return redirect()->route('rooms.show', ['room'=>$request->roomsSlug]);*/
    }
}
