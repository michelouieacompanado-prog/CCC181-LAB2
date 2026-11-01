<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Creates or updates the default administrator account.
     *
     * Admin Credentials:
     * - Email:    admin@ccc181.test
     * - Password: password123
     * - Role:     admin
     */
    public function run(): void
    {
        $admin = User::firstOrNew(['email' => 'admin@ccc181.test']);
        $admin->name = 'Administrator';
        $admin->password = Hash::make('password123');
        $admin->role = 'admin';
        $admin->email_verified_at = now();
        $admin->save();
    }
}
