<?php

namespace App\Console\Commands;

use App\Jobs\GetStaffData;
use Illuminate\Console\Command;

class RefreshStaffData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gmod:refresh_staff_data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh staff info and ranks from ulx.txt';

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
        return GetStaffData::dispatchNow();
    }
}
