<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\CompanyUser;
use App\Models\Company;
use App\Models\JobPost;
use App\Models\User;
use App\Models\Curriculum;
use App\Models\Application;

class UpdateApplicationStatusTest extends TestCase
{
    use RefreshDatabase;

    public function test_company_user_puede_actualizar_estado_de_application(): void
    {
        $company = Company::create([
            'company_name'  => 'EmpresaEjemplo',
            'description'   => 'Descripción',
            'phone'         => '30001234',
            'location'      => 'Heredia',
            'website'       => 'https://empresa.ejemplo',
            'company_size'  => 'Pequeña',
            'specialties'   => 'Tech',
            'register_date' => now(),
        ]);

        $companyUser = CompanyUser::create([
            'company_id'   => $company->company_id,
            'name'         => 'Admin',
            'username'     => 'admin_empresa',
            'lastname'     => 'Ejemplo',
            'email'        => 'admin@empresa.ejemplo',
            'password'     => bcrypt('clave123'),
            'role'         => 'admin',
            'active'       => true,
            'register_date'=> now(),
        ]);
        $this->actingAs($companyUser, 'sanctum');

        $job = JobPost::create([
            'company_id'   => $company->company_id,
            'title'        => 'Desarrollador',
            'description'  => 'Backend Laravel',
            'requirements' => '2 años experiencia',
            'publish_date' => now(),
            'salary'       => 1200.00,
            'location'     => 'San José',
        ]);

        $candidate = User::create([
            'name'              => 'Candidato',
            'lastname'          => 'Prueba',
            'username'          => 'candidato1',
            'email'             => 'candidato@prueba.com',
            'password'          => bcrypt('secreto'),
            'registration_date' => now(),
        ]);

        $curriculum = Curriculum::create([
            'user_id'     => $candidate->user_id,
            'file_path'   => '/storage/cvs/cand.pdf',
            'upload_date' => now(),
            'description' => 'CV prueba',
            'is_default'  => true,
        ]);

        $application = Application::create([
            'user_id'          => $candidate->user_id,
            'job_post_id'      => $job->job_post_id,
            'cv_id'            => $curriculum->cv_id,
            'application_date' => now(),
            'message'          => 'Postulo al puesto',
            'status'           => 'Aplicando',
        ]);

        $payload = ['status' => 'Aceptado'];
        $response = $this->putJson(
            "/api/applications/{$application->application_id}/status",
            $payload
        );

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'application' => [
                    'application_id',
                    'user_id',
                    'job_post_id',
                    'cv_id',
                    'application_date',
                    'message',
                    'status',
                ],
            ]);

        $this->assertDatabaseHas('applications', [
            'application_id' => $application->application_id,
            'status'         => 'Aceptado',
        ]);
    }

    public function test_company_user_no_puede_actualizar_si_no_es_su_empresa(): void
    {
        $companyA = Company::create([
            'company_name'  => 'EmpresaA',
            'description'   => 'DescA',
            'phone'         => '30001234',
            'location'      => 'Heredia',
            'website'       => 'https://empresa.a',
            'company_size'  => 'Mediana',
            'specialties'   => 'Tech',
            'register_date' => now(),
        ]);
        $companyB = Company::create([
            'company_name'  => 'EmpresaB',
            'description'   => 'DescB',
            'phone'         => '30005678',
            'location'      => 'Alajuela',
            'website'       => 'https://empresa.b',
            'company_size'  => 'Grande',
            'specialties'   => 'Marketing',
            'register_date' => now(),
        ]);

        $companyUserB = CompanyUser::create([
            'company_id'   => $companyB->company_id,
            'name'         => 'AdminB',
            'username'     => 'admin_b',
            'lastname'     => 'B',
            'email'        => 'adminb@empresa.b',
            'password'     => bcrypt('clave123'),
            'role'         => 'admin',
            'active'       => true,
            'register_date'=> now(),
        ]);
        $this->actingAs($companyUserB, 'sanctum');

        $jobA = JobPost::create([
            'company_id'   => $companyA->company_id,
            'title'        => 'QA Engineer',
            'description'  => 'Testing',
            'requirements' => '1 año experiencia',
            'publish_date' => now(),
            'salary'       => 800.00,
            'location'     => 'Cartago',
        ]);

        $candidate = User::create([
            'name'              => 'Candidato2',
            'lastname'          => 'Prueba2',
            'username'          => 'cand2',
            'email'             => 'cand2@prueba.com',
            'password'          => bcrypt('secreto'),
            'registration_date' => now(),
        ]);

        $curriculum = Curriculum::create([
            'user_id'     => $candidate->user_id,
            'file_path'   => '/storage/cvs/cand2.pdf',
            'upload_date' => now(),
            'description' => 'CV prueba 2',
            'is_default'  => true,
        ]);

        $application = Application::create([
            'user_id'          => $candidate->user_id,
            'job_post_id'      => $jobA->job_post_id,
            'cv_id'            => $curriculum->cv_id,
            'application_date' => now(),
            'message'          => 'Postulo al puesto A',
            'status'           => 'Aplicando',
        ]);

        $payload = ['status' => 'Rechazado'];
        $response = $this->putJson(
            "/api/applications/{$application->application_id}/status",
            $payload
        );


        

        $response
            ->assertStatus(401)
            ->assertJson([ 'message' => 'No autorizado' ]);

        $this->assertDatabaseHas('applications', [
            'application_id' => $application->application_id,
            'status'         => 'Aplicando',
        ]);
    }

    public function test_campo_status_obligatorio_y_valido(): void
    {
        $company = Company::create([
            'company_name'  => 'EmpresaTest',
            'description'   => 'DescTest',
            'phone'         => '30009999',
            'location'      => 'San José',
            'website'       => 'https://empresa.test',
            'company_size'  => 'Pequeña',
            'specialties'   => 'Finance',
            'register_date' => now(),
        ]);

        $companyUser = CompanyUser::create([
            'company_id'   => $company->company_id,
            'name'         => 'AdminTest',
            'username'     => 'admintest',
            'lastname'     => 'Test',
            'email'        => 'admintest@empresa.test',
            'password'     => bcrypt('clave123'),
            'role'         => 'admin',
            'active'       => true,
            'register_date'=> now(),
        ]);
        $this->actingAs($companyUser, 'sanctum');

        $job = JobPost::create([
            'company_id'   => $company->company_id,
            'title'        => 'DevOps Engineer',
            'description'  => 'Infraestructura',
            'requirements' => 'Docker, Kubernetes',
            'publish_date' => now(),
            'salary'       => 1300.00,
            'location'     => 'Heredia',
        ]);

        $candidate = User::create([
            'name'              => 'Candidato3',
            'lastname'          => 'Prueba3',
            'username'          => 'cand3',
            'email'             => 'cand3@prueba.com',
            'password'          => bcrypt('secreto'),
            'registration_date' => now(),
        ]);

        $curriculum = Curriculum::create([
            'user_id'     => $candidate->user_id,
            'file_path'   => '/storage/cvs/cand3.pdf',
            'upload_date' => now(),
            'description' => 'CV prueba 3',
            'is_default'  => true,
        ]);

        $application = Application::create([
            'user_id'          => $candidate->user_id,
            'job_post_id'      => $job->job_post_id,
            'cv_id'            => $curriculum->cv_id,
            'application_date' => now(),
            'message'          => 'Postulo a DevOps',
            'status'           => 'Aplicando',
        ]);

        $payloadEmpty = [];
        $responseEmpty = $this->putJson(
            "/api/applications/{$application->application_id}/status",
            $payloadEmpty
        );
        $responseEmpty->assertStatus(422);

        $payloadInvalid = ['status' => 'NoExiste'];
        $responseInvalid = $this->putJson(
            "/api/applications/{$application->application_id}/status",
            $payloadInvalid
        );
        $responseInvalid->assertStatus(422);
    }
}
