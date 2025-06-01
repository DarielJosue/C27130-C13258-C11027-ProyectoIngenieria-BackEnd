<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CompanyUser;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;


class CompanyUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         CompanyUser::create([
            'company_id' => 1,
            'name' => 'Carlos',
            'lastname' => 'Ramírez',
            'username' => 'carlos.ramirez',
            'email' => 'carlos@techsolutions.com',
            'password' => Hash::make('12345678'),
            'role' => 'admin',
            'active' => true,
            'register_date' => Carbon::now(),
        ]);

        CompanyUser::create([
            'company_id' => 2,
            'name' => 'María',
            'lastname' => 'González',
            'username' => 'maria.gonzalez',
            'email' => 'maria@greeninnovations.com',
            'password' => Hash::make('12345678'),
            'role' => 'admin',
            'active' => true,
            'register_date' => Carbon::now(),
        ]);
    }
}