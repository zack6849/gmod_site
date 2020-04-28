<?php

namespace App\Console\Commands;

use App\Jobs\UpdateUsers;
use Illuminate\Console\Command;

class RefreshUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gmod:refresh_all_users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh all steam user statistics';

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
        return UpdateUsers::dispatchNow();
    }
}
