<?php

namespace App\Http\Controllers;

use App\Models\Training;
use Illuminate\Http\Request;

class trainingController extends Controller
{
    public function create(Request $request){
        Training::create([
            'slug_room' => $request->roomsSlug,
            'coach' => $request->classCoach,
            'profile' => $request->classProfile,
            'date' => $request->classDate,
            'time_start' => $request->classTime,
            'time_end' => $request->classTimeEnd,
            'quarter' => $request->classQuarter,
            'comment' => $request->classComment ? $request->classComment : '-',
        ]);
        return redirect()->route('rooms.show', ['room'=>$request->roomsSlug]);
    }

    public function edit(Request $request)
    {
        $traning = Training::where('id', $request->id)->first();
        $traning->date = date('Y-m-d', strtotime($request->date));
        $traning->time_start = date('H:i', strtotime($request->start));
        $traning->time_end = date('H:i', strtotime($request->end));
        $traning->save();
    }

    public function delete(Request $request)
    {
        Training::where('id', $request->id)->delete();
    }
}
