<?php

use App\Rank;
use Illuminate\Database\Seeder;

class RankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ranks = [
            [
                "name" => "superadmin",
                "friendly_name" => "Owner",
                "sort_order" => 6,
                "is_staff" => true
            ],
            [
                "name" => "co-owner",
                "friendly_name" => "Co-Owner",
                "sort_order" => 5,
                "is_staff" => true
            ],
            [
                "name" => "admin",
                "friendly_name" => "Admin",
                "sort_order" => 4,
                "is_staff" => true
            ],
            [
                "name" => "mod",
                "friendly_name" => "Mod",
                "sort_order" => 3,
                "is_staff" => true
            ],
            [
                "name" => "helper",
                "friendly_name" => "Helper",
                "sort_order" => 2,
                "is_staff" => true
            ],
            [
                "name" => "trial-helper",
                "friendly_name" => "Trial Helper",
                "sort_order" => 1,
                "is_staff" => true
            ],
            [
                "name" => "user",
                "friendly_name" => "User",
                "sort_order" => -1,
                "is_staff" => false
            ],
        ];

        foreach ($ranks as $rank_data) {
            Rank::create($rank_data);
        }
    }
}
