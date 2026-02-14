<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::upsert([
            ['title' => 'super_admin'],
            ['title' => 'admin'],
            ['title' => 'dvisor'],
            ['title' => 'ExecutiveOfficer'],
            ['title' => 'EducationOfficer'],
            ['title' => 'AttendanceOfficer'],
            // ['title' => 'CulturalOfficer'],
            // ['title' => 'MediaCoordinator'],
            // ['title' => 'teacher'],
            // ['title' => 'student'],
            // ['title' => 'parent']
        ], ['title']);
    }
}
