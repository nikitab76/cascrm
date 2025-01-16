<?php

namespace App\Http\Controllers;

use App\Models\Training;
use App\Models\traning_group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MagazineController extends Controller
{
    public function index()
    {
        $trainig = Training::where('coach', Auth::user()->surname)->where('date', '>=', date('Y-m-d'))->orderBy('date', 'asc')->get();
        return view('coach.magazine', compact('trainig'));
    }

    public function show($id)
    {
        $group = traning_group::query()->where('id', $id)->first();
        $users = json_decode($group->users_list);
        return view('coach.listMagazine', compact('users'));
    }
}
