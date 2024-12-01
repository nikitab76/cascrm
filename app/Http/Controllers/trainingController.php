<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Training;
use App\Models\traning_group;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public function createTrainingCoach(Request $request)
    {
        $flag = true;
        if (isset($request->class)) {
            $class = Room::where('id', $request->class)->value('slug');
        }
        if (!isset($request->classProfile)){
            $flag = false;
            return response()->json([
                'success' => false,
                'error' => 'заполните секцию'
            ]);
            //$profile = $request->classProfile;
        }
        if (!isset($request->classDate)){
            $flag = false;
            return response()->json([
                'success' => false,
                'error' => 'заполните дату'
            ]);
        }
        if (!isset($request->classTime)){
            $flag = false;
            return response()->json([
                'success' => false,
                'error' => 'заполните время начала'
            ]);
        }
        if (!isset($request->classTimeEnd)){
            $flag = false;
            return response()->json([
                'success' => false,
                'error' => 'заполните время окончания'
            ]);
        }
        if ($flag){
            $coach = Users::where('id', $request->coach)->value('surname');
            Training::create([
                'slug_room' => $class,
                'coach' => $coach,
                'profile' => $request->classProfile,
                'date' => $request->classDate,
                'time_start' => $request->classTime,
                'time_end' => $request->classTimeEnd,
                'quarter' => 1,
                'comment' => $request->classComment ? $request->classComment : '-',
            ]);
            return response()->json([
                'success' => true,
                'error' => 'Тренировка добавлена'
            ]);
        }
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

    public function trainingProfile()
    {
        $trainig = Training::where('coach', Auth::user()->surname)->where('date', '>=', date('Y-m-d'))->orderBy('date', 'asc')->get();
        return view('coach.treningList', compact('trainig'));
    }

    public function trainingList()
    {
        $trainig = Training::where('date', '>=' , date('Y-m-d', strtotime('- 3 day')))->orderBy('date', 'asc')->get();
        return view('traingListAll', compact('trainig'));
    }

    public function groupsIndex()
    {
        return view('users.groups');
    }

    public function groupsCreate(Request $request)
    {
        //dd(json_encode($request->users));
        traning_group::create([
            'coach_id' => $request->coach,
            'group_num' => $request->numGroup,
            'users_list' => json_encode($request->users)
        ]);
        return true;
    }

    public function addTraing(Request $request)
    {
        //dd($request);
        Training::where('id', $request->traingId)->update([
            'time_start' => $request->classTimeStart,
            'time_end' => $request->classTimeEnd,
            'slug_room' => Room::where('id', $request->classAdd)->value('slug'),
        ]);
        return true;
    }
}
