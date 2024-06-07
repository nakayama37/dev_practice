<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->insert([[
            'name' => '音楽'
        ],
        [
            'name' => 'スポーツ'
        ],
        [
            'name' => '演劇・お笑い'
        ],
        [
            'name' => 'アート・文化'
        ],
        [
            'name' => '配信イベント'
        ],
     ]);
    }
}
