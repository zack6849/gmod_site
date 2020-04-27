<?php

namespace App\Http\Controllers;

use App\Console\Commands\RefreshOnlineUsers;
use App\Jobs\GetOnlineUsers;
use App\Rank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class GmodController extends Controller
{
    public function index(){
        if(!Cache::has('online_players')){
            GetOnlineUsers::dispatchNow();
        }
        $users = Cache::get('online_players');
        $ranks = Rank::whereIsStaff(true)->orderByDesc('sort_order')->get()->all();
        $online_staff_count = Cache::get('online_staff_count');
        $online_player_count = Cache::get('online_player_count');
        return view('index', compact('users', 'ranks', 'online_player_count', 'online_staff_count'));
    }
}
