<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name' => 'سعید حاجی زاده',
            'email' => 'test@gmail.com',
            'username' => 'admin',
            'password' => Hash::make('admin'),
            'mobile' => '09100000000'
        ]);

        $roleId = Role::where('title', 'super_admin')->value('id');
        $user->roles()->syncWithoutDetaching([$roleId]);
    }
}
