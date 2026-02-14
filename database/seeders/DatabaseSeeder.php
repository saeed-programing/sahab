<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\User;
use Faker\Factory;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
        ]);
        $this->call([
            UserSeeder::class,
        ]);

        // ایجاد 50 دانش‌آموز فیک
        // Student::factory()->count(50)->create();
    }
}
