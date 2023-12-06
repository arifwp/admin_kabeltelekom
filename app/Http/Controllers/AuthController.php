<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Symfony\Component\VarDumper\VarDumper;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index(){
        return view("auth.login");
    }

    public function login(Request $request) {
        $this->validate($request, [
            'email' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();
        if($user != null){
            $request->session()->put('userFullName', $user->full_name);
            return redirect()->route('admin');
        } else {
            return redirect()->route('index')->withErrors(['failed' => 'The credentials do not match our records']);
        }
    }

    public function logout(Request $request) {
        $request->session()->flush();
        return redirect()->route('index');
    }
}
