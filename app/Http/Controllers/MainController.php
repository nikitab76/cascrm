<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class MainController extends Controller
{
    public function index(){
        return view('login');
    }

    public function indexProfile()
    {
        return view('users.profile');
    }

    public function login(Request $request)
    {
        if ($user = User::where('login', $request->login)->where('password', $request->password)->first()){
            Auth::login($user);
            return redirect()->route('index.profile');
        } else {
            return redirect()->back();
        }
    }
}
