<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cityJSON = file_get_contents(
            public_path() . '/assets/psgc/cities.json'
        );
        $cities = json_decode($cityJSON, true);

        foreach ($cities as $city) {
            City::updateOrCreate(
                ['code' => $city['citymunCode']],
                ['name' => $city['citymunDesc']]
            );
        }
    }
}
