<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class ParticipantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('participants')->insert([
            [
                'event_id' => 1,
                'user_id' => 1,
                'number_of_people' => 5,
                'canceled_at' => null
            ],
            [
                'event_id' => 1,
                'user_id' => 2,
                'number_of_people' => 3,
                'canceled_at' => null
            ],
            [
                'event_id' => 2,
                'user_id' => 1,
                'number_of_people' => 2,
                'canceled_at' => null
            ],
            [
                'event_id' => 2,
                'user_id' => 2,
                'number_of_people' => 2,
                'canceled_at' => '2024-05-01 00:00:00'
            ]
           
        ]);
    }
}
