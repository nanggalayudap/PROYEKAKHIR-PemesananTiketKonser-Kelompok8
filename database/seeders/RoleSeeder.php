<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $organizerRole = Role::firstOrCreate(['name' => 'organizer']);
        $customerRole = Role::firstOrCreate(['name' => 'customer']);

        $admin = User::firstOrCreate(
            ['email' => 'admin@eventra.com'],
            ['name' => 'Admin Eventra', 'password' => Hash::make('password123')]
        );
        $admin->assignRole($adminRole);

        $organizer = User::firstOrCreate(
            ['email' => 'eo@eventra.com'],
            ['name' => 'Event Organizer', 'password' => Hash::make('password123')]
        );
        $organizer->assignRole($organizerRole);

        $customer = User::firstOrCreate(
            ['email' => 'customer@eventra.com'],
            ['name' => 'Penonton Konser', 'password' => Hash::make('password123')]
        );
        $customer->assignRole($customerRole);
    }
}