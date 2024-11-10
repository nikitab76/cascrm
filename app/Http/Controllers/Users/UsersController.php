<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\Job_title;
use App\Models\User;
use App\Models\Users;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function indexList()
    {
        return view('users.users_list');
    }

    public function createUsers(Request $request)
    {
        if (!isset($request->name)){
            $this->response['error'] = 'Поле Имя обязательно';
        }

        if (!isset($request->surname)){
            $this->response['error'] = 'Поле Фамилия обязательно';
        }

        if (!isset($request->second_name)){
            $this->response['error'] = 'Поле Отчество обязательно';
        }

        Users::create([
            'name' => $request->name,
            'surname' => $request->surname,
            'second_name' => $request->second_name,
            'job_title' => $request->job_title,
            'role' => Job_title::getRole($request->job_title)
        ]);
        return redirect()->route('users.list');
    }

    public function showUsers(string $id)
    {
        $user = Users::where('id' , $id)->first();
        return view('users.profile', compact('user'));
    }
}
