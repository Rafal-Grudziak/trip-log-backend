<?php

namespace Database\Seeders;

use App\Models\TravelPreference;
use Illuminate\Database\Seeder;

class TravelPreferenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TravelPreference::insert([
            ['name' => 'Mountains'],
            ['name' => 'Lakes'],
            ['name' => 'Forests'],
            ['name' => 'Beaches'],
            ['name' => 'Deserts'],
            ['name' => 'Islands'],
            ['name' => 'National Parks'],
            ['name' => 'Natural Reserves'],
            ['name' => 'Jungles'],
            ['name' => 'Waterfalls'],
            ['name' => 'Seas'],
            ['name' => 'Cities'],
            ['name' => 'Villages'],
            ['name' => 'Museums'],
            ['name' => 'Theatres'],
            ['name' => 'Historic places'],
            ['name' => 'Monuments'],
            ['name' => 'Temples'],
            ['name' => 'Churches'],
            ['name' => 'Palaces'],
            ['name' => 'Castles'],
            ['name' => 'Art Galleries'],
            ['name' => 'Festivals'],
            ['name' => 'Cathedrals'],
            ['name' => 'Main Square'],
            ['name' => 'Cafe'],
            ['name' => 'Restaurant'],
            ['name' => 'Bars'],
            ['name' => 'Bakeries'],
            ['name' => 'Zoos'],
            ['name' => 'Gardens'],
            ['name' => 'Sports Arenas'],
            ['name' => 'Beaches (for activity)'],
            ['name' => 'Parks'],
            ['name' => 'Hiking Trails'],
            ['name' => 'Airports'],
            ['name' => 'Train Stations'],
            ['name' => 'Ports'],
            ['name' => 'Bus stations'],
        ]);
    }
}
