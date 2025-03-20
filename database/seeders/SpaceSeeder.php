<?php

namespace Database\Seeders;

use App\Models\Space;
use Illuminate\Database\Seeder;

class SpaceSeeder extends Seeder
{
    public function run()
    {
        Space::factory()->count(30)->create(); // Create 10 random Space records
    }
}
