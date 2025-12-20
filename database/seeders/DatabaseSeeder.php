<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = [
            [
                'name'  => 'User Alpha',
                'email' => 'user.alpha@demo.app',
            ],
            [
                'name'  => 'User Beta',
                'email' => 'user.beta@demo.app',
            ],
            [
                'name'  => 'User Gamma',
                'email' => 'user.gamma@demo.app',
            ],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']], // unique key
                [
                    'name'              => $userData['name'],
                    'password'          => Hash::make('mini@Wallet025'),
                    'balance'           => 1000,
                    'email_verified_at' => now(),
                ]
            );
        }

    }
}
