<?php

namespace Database\Seeders;

use App\Models\Developer;
use Illuminate\Database\Seeder;

class DeveloperSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Developer::factory()->seniority()->create();
        Developer::factory()->seniority(2)->create();
        Developer::factory()->seniority(3)->create();
        Developer::factory()->seniority(4)->create();
        Developer::factory()->seniority(5)->create();
    }
}
