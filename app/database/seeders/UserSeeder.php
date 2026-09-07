<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => Role::Admin,
            'email_verified_at' => now(),
        ]);

        // Agents
        User::create([
            'name' => 'Sarah Chen',
            'email' => 'sarah@example.com',
            'password' => Hash::make('password'),
            'role' => Role::Agent,
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'James Wilson',
            'email' => 'james@example.com',
            'password' => Hash::make('password'),
            'role' => Role::Agent,
            'email_verified_at' => now(),
        ]);

        // Regular Users
        User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('password'),
            'role' => Role::User,
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Emily Brown',
            'email' => 'emily@example.com',
            'password' => Hash::make('password'),
            'role' => Role::User,
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Michael Davis',
            'email' => 'michael@example.com',
            'password' => Hash::make('password'),
            'role' => Role::User,
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Lisa Anderson',
            'email' => 'lisa@example.com',
            'password' => Hash::make('password'),
            'role' => Role::User,
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Robert Taylor',
            'email' => 'robert@example.com',
            'password' => Hash::make('password'),
            'role' => Role::User,
            'email_verified_at' => now(),
        ]);
    }
}
