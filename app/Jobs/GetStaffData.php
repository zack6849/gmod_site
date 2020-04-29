<?php

namespace App\Jobs;

use App\Helpers\KeyValueReader;
use App\Rank;
use App\SteamUser;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Sentry\Breadcrumb;
use SteamCondenser\Community\SteamId;
use function Sentry\addBreadcrumb;

class GetStaffData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $ranks_file_content = \Storage::get('users.txt');
        $parser = new KeyValueReader($ranks_file_content);
        $data = $parser->parse();
        foreach ($data as $steam_id => $user_data){
            if(!array_key_exists('group', $user_data)){
                continue;
            }
            $group = $user_data['group'];
            $steam_user = SteamUser::findOrCreate($steam_id);
            if($steam_user == null){
                continue;
            }
            $rank = Rank::findOrCreate($group);
            $steam_user->rank_id = $rank->id;
            $steam_user->save();
        }
    }
}
