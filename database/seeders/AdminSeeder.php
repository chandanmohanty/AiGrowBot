<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@aigrowbot.local'],
            [
                'name'     => 'Administrator',
                'password' => Hash::make('ChangeMe!123'),
            ]
        );
        $admin->syncRoles(['Admin']);
    }
}
