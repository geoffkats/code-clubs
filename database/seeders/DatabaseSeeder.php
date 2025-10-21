<?php

namespace Database\Seeders;

use App\Models\School;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Run existing seeders first
        $this->call([
            SampleDataSeeder::class,
            DashboardDataSeeder::class,
        ]);

        // Run V2.5.0 features seeder
        $this->call(V2FeaturesSeeder::class);
    }
}
