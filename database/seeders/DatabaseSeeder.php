<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Category;
use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\EventCategory;
use App\Models\Location;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        
        Event::factory(100)->create();
        EventCategory::factory(100)->create();
        Location::factory(100)->create();
        $this->call([
            UserSeeder::class,
            ParticipantSeeder::class,
            CategorySeeder::class,
        ]);

        


    }
}
