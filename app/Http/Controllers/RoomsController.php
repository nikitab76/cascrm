<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Training;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RoomsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rooms = Room::all();
        return view('Main.rooms', compact('rooms'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //dd($request->roomName);
        Room::create([
            'title' => $request->roomName,
            'slug' =>Str::slug($request->roomName)
        ]);
        return redirect()->route('rooms.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $room['room'] = Room::where('slug', $id)->first();
        $info = self::infoTraining($id);
        if (isset($info) && !empty($info)){
            $room['info'] = $info;
        }
        $room['day'] = ['2024-10-05', '2024-10-06'];
        return view('Main.roomProfile', compact('room'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Room::find($id)->delete();
        return redirect()->route('rooms.index');
    }

    public function infoTraining($id){
        $training = Training::where('slug_room', $id)/*->where('date', '>=', $to)->where('date' , '<=', $from)*/->get();
        $list = [];
        foreach ($training as $train){
            $row['coach'] = $train->coach;
            if ($train->quarter == 1){
                $row['quarter'] = 'весь зал';
            } elseif ($train->quarter == 2){
                $row['quarter'] = '2/4';
            } else {
                $row['quarter'] = '1/4';
            }
            $row['comment'] = $train->comment;
            $row['profile'] = $train->profile;
            $row['time'] = $train->time;
            $row['date'] = $train->date;
            $list[]= $row;
        }
        return $list;
    }

    public function showTable(Request $request){
        $trainings = Training::where('slug_room', $request->id)->get();
        $list = [];
        foreach ($trainings as $training){
            $row['id'] = $training->id;
            $row['name'] = $training->coach;
            $row['profile'] = $training->profile;
            $row['comment'] = $training->comment;
            $row['start'] = $training->date . 'T' . $training->time_start . ':00';
            $row['end'] = $training->date . 'T' . $training->time_end . ':00';
            $row['quarter'] = self::colorEvent($training->quarter);
            $row['body'] = '<strong>Начало тренировки:</strong> ' . $training->time_start . '<br>' .
            '<strong>Конец тренировки:</strong> ' . $training->time_end . '<br>' .
                '<strong>Инструктор:</strong> ' . $training->coach . '<br>'.
            '<strong>Комментарий:</strong> ' . $training->comment;
            $list[] = $row;
        }
        return $list;
    }
    public function colorEvent($data){
        switch ($data){
            case 4:
                return '#9379d9';
            case 2:
                return '';
            case 1:
                return '#d5b42c';
        }
    }
}
