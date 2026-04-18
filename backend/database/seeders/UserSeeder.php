<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * @var list<array{name: string, email: string, password: string, role: \App\Enums\UserRole}>
     */
    private array $users = [
        [
            'name' => 'SaaSKit Admin',
            'email' => 'admin@example.com',
            'password' => 'password',
            'role' => UserRole::ADMIN,
        ],
        [
            'name' => 'SaaSKit User',
            'email' => 'user@example.com',
            'password' => 'password',
            'role' => UserRole::USER,
        ],
        [
            'name' => 'Operations Manager',
            'email' => 'ops@example.com',
            'password' => 'password',
            'role' => UserRole::USER,
        ],
        [
            'name' => 'Data Analyst',
            'email' => 'analyst@example.com',
            'password' => 'password',
            'role' => UserRole::USER,
        ],
    ];

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        foreach ($this->users as $user) {
            User::query()->updateOrCreate(
                ['email' => $user['email']],
                [
                    'name' => $user['name'],
                    'password' => $user['password'],
                    'role' => $user['role'],
                    'email_verified_at' => now(),
                ]
            );
        }
    }
}
