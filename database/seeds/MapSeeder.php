<?php

use App\Map;
use Illuminate\Database\Seeder;

class MapSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $maps = [
            [
                "name" => "ph_amaya",
                "image_url" => "ph_amaya.jpg",
                "friendly_name" => "Amaya"
            ],
            [
                "name" => "ph_lockup",
                "friendly_name" => "Lockup",
                "image_url" => "ph_lockup.jpg"
            ],
            [
                "name" => "ph_parkinglot",
                "image_url" => "ph_parkinglot.jpg",
                "friendly_name" => "Parking Lot"
            ],
        ];

        foreach ($maps as $map_data) {
            Map::create($map_data);
        }
    }
}
