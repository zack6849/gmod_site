<?php

namespace App\Http\Controllers;

use App\Console\Commands\RefreshOnlineUsers;
use App\Jobs\GetOnlineUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class GmodController extends Controller
{
    public function index(){
        if(!Cache::has('online_players')){
            GetOnlineUsers::dispatchNow();
        }
        $users = Cache::get('online_players');
        return view('index', compact('users'));
    }
}
