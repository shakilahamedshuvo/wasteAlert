<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\user;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        user::create([
            'name' => 'team1',
            'email' => 'shakilahamedshuvooo@gmail.com',
            'password' => bcrypt('team1'),
            'role' => 'team',

        ]);
    }
}
