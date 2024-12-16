<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Controllers\testcontroller;
use App\Models\Job_title;
use App\Models\User;
use App\Models\Users;
use App\Models\UsersDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    public function indexList()
    {
        return view('users.users_list');
    }

    public function createUsers(Request $request)
    {
        if (isset($request->file)){
            $file = $request->file('file');
            testcontroller::exel($file);
            return true;
        }
        if (!isset($request->name)){
            $this->response['error'] = 'Поле Имя обязательно';
        }

        if (!isset($request->surname)){
            $this->response['error'] = 'Поле Фамилия обязательно';
        }

        if (!isset($request->second_name)){
            $this->response['error'] = 'Поле Отчество обязательно';
        }
        if (isset($request->phone)){
            $phone = trim($request->phone);
            $phone = str_replace(' ', '', $phone);
            $phone = str_replace('-', '', $phone);
            $onesim = substr($phone, 0, 1);
            if ($onesim == '+'){
                $phone = substr($phone, 1);
            } elseif ($onesim == 8){
                $phone = '7' . substr($phone, 1);
            }
            if(mb_strlen($phone) != 11){
                return null;
            }
        }

        Users::create([
            'name' => $request->name,
            'surname' => $request->surname,
            'second_name' => $request->second_name,
            'job_title' => $request->job_title,
            'phone' => $phone,
            'login' => $phone,
            'password' => $phone,
            'role' => Job_title::getRole($request->job_title)
        ]);
        return redirect()->route('users.list');
    }

    public function showUsers(string $id)
    {
        $user = Users::where('id' , $id)->first();
        if($user->role == 'user'){
            if ($document = UsersDocument::where('user_id', $user->id)->first())
            {
                $user->coach = $document->coach;
                $user->nosology = $document->nosology;
                $user->representative = $document->representative;
                $user->medical_certificate = $document->medical_certificate;
            }

        }
        return view('users.profile', compact('user'));
    }

    public function updatePassword(Request $request)
    {
        if (Auth::user()->id == $request->user){
            $user = Users::where('id', $request->user)->first();
            if (isset($request->oldPassword) && isset($request->newPassword)){
                if ($request->oldPassword == $user->password) {

                    if ($request->oldPassword != $request->newPassword) {
                        $request->newPassword = trim($request->newPassword);
                        if (strlen($request->newPassword) >= 5) {
                            $user->update(['password' => $request->newPassword]);
                            return response()->json([
                                'success' => true,
                                'message' => 'Пароль успешно изменен!'
                            ]);;
                        } else {
                            return response()->json([
                                'success' => false,
                                'message' => 'Новый пароль не может быть короче 5 символов'
                            ]);;
                        }
                    } else {
                        return response()->json([
                            'success' => false,
                            'message' => 'Новый пароль не может равняться старому!'
                        ]);
                    }
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Cтарый пароль введен не верно!'
                    ]);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Введите новые данные!'
                ]);;
            }
        }
    }

    public function usersList()
    {
        $users = Users::all();
        $data = [];
        foreach ($users as $user){
            $row['user']['name'] = $user->fullName();
            $row['user']['id'] = $user->id;
            $row['phone'] = $user->phone;
            $row['job'] = $user->job_title;
            $data[] = $row;
        }

        return response()->json([
            'data' => $data,
            'success' => true
        ]);
    }

    public function engagedList()
    {
        return view('users.engagedList');
    }

    public function getCoachById($id)
    {
        $coach = Users::where('id', $id)->first();
        return $coach->fullName();
    }

    public function engagedListGet()
    {
        $users = Users::where('role', 'user')->join('users_documents', 'users.id', '=', 'users_documents.user_id')->get();
        $data = [];
        foreach ($users as $user){
            $row['user']['name'] = $user->fullName();
            $row['user']['id'] = $user->user_id;
            $row['coach']['name'] = self::getCoachById($user->coach);
            $row['coach']['id'] = $user->coach;
            $row['repres'] = $user->representative;
            $row['mc'] = $user->medical_certificate;
            $row['nosology'] = $user->nosology;
            $data[] = $row;
        }

        return response()->json([
            'data' => $data,
            'success' => true
        ]);

    }
}
