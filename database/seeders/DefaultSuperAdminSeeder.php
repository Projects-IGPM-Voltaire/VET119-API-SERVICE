<?php

namespace Database\Seeders;

use App\Models\HealthCenter;
use App\Models\User;
use Illuminate\Database\Seeder;
use Exception;

class DefaultSuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $payload = [
            'first_name' => 'Sebastian Curtis',
            'last_name' => 'Lavarias',
            'birthday' => '2000-05-21',
            'mobile_number' => '09972217704',
            'password' => 'password',
        ];

        try {
            User::create([
                'first_name' => $payload['first_name'],
                'last_name' => $payload['last_name'],
                'birthday' => $payload['birthday'],
                'mobile_number' => $payload['mobile_number'],
                'password' => bcrypt($payload['password']),
                'level' => 'superadmin',
            ]);
        } catch (Exception $e) {
            info($e->getMessage());
        }
    }
}
