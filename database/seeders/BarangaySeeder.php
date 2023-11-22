<?php

namespace Database\Seeders;

use App\Models\Barangay;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BarangaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $barangayJSON = file_get_contents(
            public_path() . '/assets/psgc/barangays.json'
        );
        $barangays = json_decode($barangayJSON, true);

        foreach ($barangays as $barangay) {
            Barangay::updateOrCreate(
                ['code' => $barangay['brgyCode']],
                [
                    'name' => $barangay['brgyDesc'],
                    'city_code' => $barangay['citymunCode'],
                ]
            );
        }
    }
}
