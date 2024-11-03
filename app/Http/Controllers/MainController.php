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
        $user = Auth::user();
        return view('users.profile', compact('user'));
    }

    public function login(Request $request)
    {
        if ($user = User::where('login', $request->login)->where('password', $request->password)->first()){
            Auth::login($user);
            session()->put('auth', 1);
            return redirect()->route('index.profile');
        } else {
            return redirect()->back();
        }
    }

    public function logout(Request $request)
    {
        $request->session()->forget('auth');
        return redirect()->route('index');
    }
}
