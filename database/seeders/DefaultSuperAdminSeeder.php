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
            'first_name' => 'Chester',
            'last_name' => 'Tan',
            'mobile_number' => '09171529122',
            'password' => 'password',
            'email' => 'rey.tan@student.ateneo.edu'
        ];

        try {
            User::create([
                'first_name' => $payload['first_name'],
                'last_name' => $payload['last_name'],
                'mobile_number' => $payload['mobile_number'],
                'password' => bcrypt($payload['password']),
                'level' => 'admin',
                'email' => $payload['email'],
                'email_verified_at' => now()
            ]);
        } catch (Exception $e) {
            info($e->getMessage());
        }
    }
}
