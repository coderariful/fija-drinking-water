<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin',
            'phone' => '00000',
            'password' => bcrypt('123456'),
            'user_type' => USER_ADMIN
        ]);

        User::factory()->create([
            'name' => 'Employee',
            'phone' => '11111',
            'password' => bcrypt('123456'),
            'user_type' => USER_EMPLOYEE
        ]);
    }
}
