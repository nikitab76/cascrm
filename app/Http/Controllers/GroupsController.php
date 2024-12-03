<?php

namespace App\Http\Controllers;

use App\Models\traning_group;
use Illuminate\Http\Request;

class GroupsController extends Controller
{
    public function index()
    {
        $groups = traning_group::all()->groupBy('coach_id');
        return view('groups', compact('groups'));
    }
}
