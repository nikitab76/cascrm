<?php

namespace App\Http\Controllers;

use App\Models\MagazineVisits;
use App\Models\Training;
use App\Models\traning_group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use function PHPUnit\Framework\isEmpty;

class MagazineController extends Controller
{
    public function index()
    {
        $trainig = Training::where('coach', Auth::user()->surname)/*->where('date', '>=', date('Y-m-d'))*/->orderBy('date', 'asc')->get();
        return view('coach.magazine', compact('trainig'));
    }

    public function show($id, $day, $profile)
    {
        $group = traning_group::query()->where('id', $id)->first();
        $users = json_decode($group->users_list);
        $data = [];
        $dayChekit = MagazineVisits::query()->where('date', $day)->get();
        foreach ($dayChekit as $chek){
            foreach ($users as $user){
                if ($chek->user == $user){
                    if ($chek->on_visit){
                        $data[$user] = 1;
                    } else {
                        $data[$user] = 0;
                    }
                }
            }
        }
        if (empty($data)){
            foreach ($users as $user){
                $data[$user] = 0;
            }
        }
        return view('coach.listMagazine', compact(['data','day', 'profile']));
    }

    public function noteUsers(Request $request)
    {
        if (isset($request->users))
        {
            foreach ($request->users as $user => $value){
                $user = str_replace('user_', '', trim($user));
                MagazineVisits::updateOrCreate([
                    'user' => $user,
                    'profile' => $request->profile,
                    'date' => $request->day,
                    'coach' => $request->coach
                ],
                ['on_visit' => $value]);
            }
        }
        return response()->json([
            'success' => true,
            'message' => 'Занимающиеся отмечены!'
        ]);
    }

    public function showMagazinesCoach()
    {
        $groups = Training::all()->groupBy('coach');

        foreach ($groups as $coach => $training){
            foreach ($training as $train){
                if(isset($train->group)){
                    $users = json_decode(traning_group::query()->where('id', $train->group)->value('users_list'));
                    $dayChekit = MagazineVisits::query()->where('date', $train->date)->get();
                    $data = [];
                    foreach ($dayChekit as $chek){
                        foreach ($users as $user){
                            if ($chek->user == $user){
                                if ($chek->on_visit){
                                    $data[$user] = 1;
                                } else {
                                    $data[$user] = 0;
                                }
                            }
                        }
                    }
                    $train->users = $data;
                }
            }
        }

        return view('coach.magazinesCoach', compact('groups'));
    }
}
