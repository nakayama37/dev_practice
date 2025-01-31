<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([[
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => HASH::make('pass123'),
            'role' => 1
        ],
        [
            'name' => 'manager',
            'email' => 'manager@manager.com',
            'password' => HASH::make('pass123'),
            'role' => 5
        ],
        [
            'name' => 'test',
            'email' => 'test@test.com',
            'password' => HASH::make('pass123'),
            'role' => 9
        ]
     ]);
    }
}
