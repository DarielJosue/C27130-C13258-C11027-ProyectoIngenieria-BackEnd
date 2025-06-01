<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Company;
use Carbon\Carbon;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         Company::create([
            'company_name' => 'Tech Solutions',
            'description' => 'Leading software development company.',
            'phone' => '123456789',
            'location' => 'San José, Costa Rica',
            'website' => 'https://techsolutions.com',
            'company_size' => '51-200',
            'specialties' => 'Software Development, AI, Cloud Computing',
            'register_date' => Carbon::now(),
        ]);

        Company::create([
            'company_name' => 'Green Innovations',
            'description' => 'Eco-friendly technology solutions.',
            'phone' => '987654321',
            'location' => 'Alajuela, Costa Rica',
            'website' => 'https://greeninnovations.com',
            'company_size' => '11-50',
            'specialties' => 'Renewable Energy, IoT',
            'register_date' => Carbon::now(),
        ]);
    }
}