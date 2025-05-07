<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'adminn',
            'username' => 'admin',
            'lastname' => 'last_name',
            'email' => 'admin@example.com',
            'password' => bcrypt('Admin1234'),
            'created_at' => now(),
            'updated_at' => now(),
            'registration_date' => now(),
        ]);
    }
}