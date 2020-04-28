<?php

namespace App\Console\Commands;

use App\Jobs\GetOnlineUsers;
use Illuminate\Console\Command;

class RefreshOnlineUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gmod:refresh_players';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh online player stats';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        return GetOnlineUsers::dispatchNow();
    }
}
