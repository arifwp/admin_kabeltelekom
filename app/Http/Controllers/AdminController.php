<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{
    public function index(){
        $userFullName = Session::get('userFullName');
        if($userFullName == null){
            return redirect()->route('index')->withErrors(['failed' => 'You have to login first']);
        }

        $data = User::all();
        return view("admin.index", compact("data"));
    }

    public function dashboard(){
        $userFullName = Session::get('userFullName');
        if($userFullName == null){
            return redirect()->route('index')->withErrors(['failed' => 'You have to login first']);
        }
        
        $users = User::select(DB::raw("COUNT(*) as count"), DB::raw("MONTHNAME(created_at) as month_name"))
                    ->whereYear('created_at', date('Y'))
                    ->groupBy(DB::raw("Month(created_at)"))
                    ->pluck('count', 'month_name');
        $labels = $users->keys();
        $userData = $users->values();

        $totalClient = DB::table('users')
        ->count();

        $activeClient = DB::table('users')
        ->where('status', 'ACTIVE')
        ->count();

        $suspendedClient = DB::table('users')
        ->where('status', 'SUSPEND')
        ->count();

        $installedClient = DB::table('users')
        ->where('status', 'INSTALLED')
        ->count();

        $data = [
            'totalClient' => $totalClient,
            'activeClient' => $activeClient,
            'suspendedClient' => $suspendedClient,
            'installedClient' => $installedClient,
        ];
        return view("admin.dashboard", compact("labels", "userData", "data"));
    }
}
