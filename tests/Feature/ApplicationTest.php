<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Company;
use App\Models\JobPost;
use App\Models\Curriculum;
use App\Models\Application;

class ApplicationTest extends TestCase
{
    use RefreshDatabase;

    public function test_usuario_autenticado_puede_aplicar_a_jobpost(): void
    {
        $user = User::create([
            'name'              => 'Carlos',
            'lastname'          => 'Ramírez',
            'username'          => 'carlosr',
            'email'             => 'carlos@example.com',
            'password'          => bcrypt('secreto123'),
            'registration_date' => now(),
        ]);
        $this->actingAs($user, 'sanctum');

        $company = Company::create([
            'company_name'  => 'Empresa Prueba S.A.',
            'description'   => 'Descripción de ejemplo',
            'phone'         => '22223333',
            'location'      => 'Heredia',
            'website'       => 'https://empresa.prueba',
            'company_size'  => 'Mediana',
            'specialties'   => 'Desarrollo, Testing',
            'register_date' => now(),
        ]);

        $job = JobPost::create([
            'company_id'   => $company->company_id,
            'title'        => 'Ingeniero de Software',
            'description'  => 'Responsable de backend Laravel',
            'requirements' => '3 años de experiencia',
            'publish_date' => now(),
            'salary'       => 1200.00,
            'location'     => 'San José',
        ]);

        $curriculum = Curriculum::create([
            'user_id'     => $user->user_id,
            'file_path'   => '/storage/cvs/carlos.pdf',
            'upload_date' => now(),
            'description' => 'CV de Carlos',
            'is_default'  => true,
        ]);

        $payload = [
            'job_post_id' => $job->job_post_id,
            'cv_id'       => $curriculum->cv_id,
            'message'     => 'Me interesa mucho este puesto',
        ];
        $response = $this->postJson('/api/job-posts/apply', $payload);

        $response
            ->assertStatus(201)
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
            'user_id'     => $user->user_id,
            'job_post_id' => $job->job_post_id,
            'cv_id'       => $curriculum->cv_id,
            'status'      => 'Aplicando',
        ]);

        $applicationId = $response->json('application.application_id');
        $application = Application::find($applicationId);

        $this->assertInstanceOf(User::class,       $application->user);
        $this->assertInstanceOf(JobPost::class,    $application->jobPost);
        $this->assertInstanceOf(Curriculum::class, $application->curriculum);

        $this->assertEquals($user->user_id,        $application->user->user_id);
        $this->assertEquals($job->job_post_id,     $application->jobPost->job_post_id);
        $this->assertEquals($curriculum->cv_id,    $application->curriculum->cv_id);
    }

    public function test_usuario_no_puede_aplicar_dos_veces_al_mismo_jobpost(): void
    {
        $user = User::create([
            'name'              => 'Laura',
            'lastname'          => 'González',
            'username'          => 'laurag',
            'email'             => 'laura@example.com',
            'password'          => bcrypt('miclave'),
            'registration_date' => now(),
        ]);
        $this->actingAs($user, 'sanctum');

        $company = Company::create([
            'company_name'  => 'Empresa Demo',
            'description'   => 'Demo Company',
            'phone'         => '88887777',
            'location'      => 'Alajuela',
            'website'       => 'https://demo.company',
            'company_size'  => 'Pequeña',
            'specialties'   => 'Marketing',
            'register_date' => now(),
        ]);
        $job = JobPost::create([
            'company_id'   => $company->company_id,
            'title'        => 'Marketing Manager',
            'description'  => 'Responsable de estrategias',
            'requirements' => '5 años experiencia',
            'publish_date' => now(),
            'salary'       => 1000.00,
            'location'     => 'Cartago',
        ]);

        $curriculum = Curriculum::create([
            'user_id'     => $user->user_id,
            'file_path'   => '/storage/cvs/laura.pdf',
            'upload_date' => now(),
            'description' => 'CV de Laura',
            'is_default'  => true,
        ]);

        $payload = [
            'job_post_id' => $job->job_post_id,
            'cv_id'       => $curriculum->cv_id,
            'message'     => '¡Estoy muy interesada!',
        ];
        $response1 = $this->postJson('/api/job-posts/apply', $payload);
        $response1->assertStatus(201);

        $response2 = $this->postJson('/api/job-posts/apply', $payload);
        $response2
            ->assertStatus(409)
            ->assertJson([ 'message' => 'Ya has aplicado a esta publicación' ]);

        $this->assertDatabaseCount('applications', 1);
        $this->assertDatabaseHas('applications', [
            'user_id'     => $user->user_id,
            'job_post_id' => $job->job_post_id,
        ]);
    }

    public function test_usuario_no_autenticado_no_puede_aplicar(): void
    {
        $userForCv = User::create([
            'name'              => 'UsuarioCV',
            'lastname'          => 'SinLogin',
            'username'          => 'usuariocv',
            'email'             => 'usuariocv@example.com',
            'password'          => bcrypt('cualquier'),
            'registration_date' => now(),
        ]);

        $company = Company::create([
            'company_name'  => 'Mi Empresa',
            'description'   => 'Otra Empresa',
            'phone'         => '11112222',
            'location'      => 'San José',
            'website'       => 'https://miempresa.test',
            'company_size'  => 'Grande',
            'specialties'   => 'Consultoría',
            'register_date' => now(),
        ]);
        $job = JobPost::create([
            'company_id'   => $company->company_id,
            'title'        => 'Consultor TI',
            'description'  => 'Se busca consultor',
            'requirements' => '10 años experiencia',
            'publish_date' => now(),
            'salary'       => 2000.00,
            'location'     => 'Heredia',
        ]);

        $curriculum = Curriculum::create([
            'user_id'     => $userForCv->user_id,
            'file_path'   => '/storage/cvs/usuariocv.pdf',
            'upload_date' => now(),
            'description' => 'CV válido aunque no logueado',
            'is_default'  => false,
        ]);

        $payload = [
            'job_post_id' => $job->job_post_id,
            'cv_id'       => $curriculum->cv_id,
            'message'     => 'Quiero este trabajo',
        ];
        $response = $this->postJson('/api/job-posts/apply', $payload);

        $response
            ->assertStatus(401)
            ->assertJson([ 'message' => 'Unauthenticated.' ]);

        $this->assertDatabaseCount('applications', 0);
    }
}
