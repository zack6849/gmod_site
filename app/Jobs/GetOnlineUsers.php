<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use SteamCondenser\Servers\Sockets\SteamSocket;
use SteamCondenser\Servers\SourceServer;

class GetOnlineUsers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $server;

    /**
     * Create a new job instance.
     *
     * @return void
     * @throws \SteamCondenserException
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
        Cache::put('online_players', $players, now()->addMinutes(5));
        Cache::put('online_players_timestamp', now());
    }
}
