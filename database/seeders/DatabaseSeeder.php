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
        $school = School::query()->first() ?: School::create([
            'school_name' => 'Demo School',
            'address' => '123 Demo Ave',
            'contact_email' => 'admin@demo.school',
        ]);

        User::query()->firstOrCreate(
            ['email' => 'admin@codeclub.local'],
            [
                'name' => 'Admin User',
                'password' => 'Password123!',
                'school_id' => $school->id,
                'user_name' => 'admin_user',
                'user_role' => 'admin',
            ]
        );
    }
}
