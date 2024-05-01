<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{

    public function run(): void
    {
        $superUser = User::firstOrCreate([
            'name' => 'Super',
            'email' => 'super@super',
            'password' => Hash::make('super'),
            'current_team_id' => 0
        ]);
        $superUser->assignRole('super');
        $superUser->givePermissionTo(['admin.home', 'admin.settings', 'admin.full']);

        $adminUser = User::firstOrCreate([
            'name' => 'Admin',
            'email' => 'admin@admin',
            'password' => Hash::make('admin'),
            'current_team_id' => 0
        ]);

        $adminUser->assignRole('admin')->givePermissionTo( ['admin.home', 'admin.settings']);
        $staffUser = User::firstOrCreate([
            'name' => 'Staff',
            'email' => 'staff@staff',
            'password' => Hash::make('staff'),
            'current_team_id' => 0
        ]);

        $staffUser->assignRole('staff')->givePermissionTo(['admin.home']);

        $clientUser = User::firstOrCreate([
            'name' => 'guest',
            'email' => 'guest@guest',
            'password' => Hash::make('guest'),
            'invited_by' => 3, 
            'current_team_id' => 0
        ]);

        $clientUser->assignRole('guest');
    }
}
