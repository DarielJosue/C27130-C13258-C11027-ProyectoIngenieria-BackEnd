<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\JobPost;
use Carbon\Carbon;
class JobPostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        JobPost::create([
            'company_id' => 1,
            'title' => 'Desarrollador Backend',
            'description' => 'Buscamos un desarrollador backend con experiencia en Laravel y Node.js.',
            'requirements' => 'Experiencia en PHP, Laravel, Node.js, MySQL.',
            'salary' => 1500,
            'location' => 'Remoto',
            'publish_date' => Carbon::now(),
        ]);

        JobPost::create([
            'company_id' => 2,
            'title' => 'Diseñador UI/UX',
            'description' => 'Se busca diseñador UI/UX con experiencia en Figma y Adobe XD.',
            'requirements' => 'Experiencia en diseño de interfaces, prototipado, herramientas de diseño.',
            'salary' => 1200,
            'location' => 'Oficina',
            'publish_date' => Carbon::now(),
        ]);
    }
}