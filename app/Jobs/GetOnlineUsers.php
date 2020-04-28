<?php

namespace App\Jobs;

use App\SteamUser;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use SteamCondenser\Exceptions\SteamCondenserException;
use SteamCondenser\Servers\Sockets\SteamSocket;
use SteamCondenser\Servers\SourceServer;
use SteamCondenser\Servers\SteamPlayer;

class GetOnlineUsers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $server;

    /**
     * Create a new job instance.
     *
     * @return void
     * @throws SteamCondenserException
     */
    public function __construct()
    {
        $this->server = new SourceServer(env('SRCDS_HOST', 'localhost'), env('SRCDS_PORT', 27015));
        $this->server->initialize();
        SteamSocket::setTimeout(50000);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $players = $this->server->getPlayers(env('SRCDS_RCON_PASS'));
        //cache ttl should be slightly longer than the cronjob, so in theory we won't have to trigger the job on http request.
        Cache::put('online_players', $players, now()->addMinutes(6));
        Cache::put('online_player_count', count($players), now()->addMinutes(6));
        Cache::put('online_players_timestamp', now());

        $staff_players = SteamUser::whereHas('rank', function(Builder $builder){
            return $builder->where('is_staff', '=', true);
        })->get()->all();

        /** @var SteamUser $player */
        foreach ($staff_players as $player){
            $player->updateStatistics($player->getProfile());
        }

        $online_staff = 0;
        /** @var SteamPlayer $player */
        foreach ($players as $player){
            if(strlen(trim($player->getSteamId()))== 0){
                continue;
            }
            $steam_user = SteamUser::findOrCreate($player->getSteamId());
            if($steam_user == null){
                continue;
            }
            if(!$steam_user->isStaff()){
                //we already fetched this a second ago if they're staff.
                $steam_user->updateStatistics($steam_user->getProfile());
            }else{
                $online_staff++;
            }
        }
        Cache::put('online_staff_count', $online_staff);
    }
}
