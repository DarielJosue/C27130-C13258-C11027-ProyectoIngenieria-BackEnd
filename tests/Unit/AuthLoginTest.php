<?php

namespace Tests\Unit;

use Tests\TestCase;                    // ← Importa TU TestCase de Laravel
use App\Models\User;
use App\Models\CompanyUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Controllers\Api\AuthController;

class AuthLoginTest extends TestCase      // ← Extiende de Tests\TestCase (Laravel)
{
    use RefreshDatabase;                // Refresca la BD entre cada test

    public function test_it_logs_in_applicant_successfully()
    {
        // 1) Creamos un usuario “applicant” en la BD usando factory:
        $user = User::factory()->create([
            'email'    => 'test@example.com',
            'password' => Hash::make('Password123'),
        ]);

        // 2) Preparamos un Request “fake” con credenciales válidas
        $request = new Request([
            'loginInput' => 'test@example.com',
            'password'   => 'Password123',
        ]);

        // 3) Llamamos directamente al controlador de login
        $controller = new AuthController();
        $response   = $controller->login($request);

        // 4) Verificamos que retorne código 200 y mensaje esperado
        $this->assertEquals(200, $response->getStatusCode());
        $responseData = $response->getData(true);
        $this->assertEquals('Inicio de sesión exitoso', $responseData['message']);
        $this->assertArrayHasKey('token', $responseData);
    }

    public function test_it_logs_in_company_user_successfully()
    {
        // 1) Creamos un usuario de tipo “company” en la BD
        $companyUser = CompanyUser::factory()->create([
            'email'    => 'company@test.com',
            'password' => Hash::make('CompanyPass123'),
        ]);

        // 2) Preparamos el Request para login de company
        $request = new Request([
            'loginInput' => 'company@test.com',
            'password'   => 'CompanyPass123',
        ]);

        // 3) Llamamos al controlador
        $controller   = new AuthController();
        $response     = $controller->login($request);
        $responseData = $response->getData(true);

        // 4) Verificamos que retorne “user_type = company” y devuelva company_id
        $this->assertEquals('company', $responseData['user_type']);
        $this->assertArrayHasKey('company_id', $responseData);
    }

    public function test_it_rejects_invalid_credentials()
    {
        // 1) Creamos un usuario con contraseña “GoodPassword”
        User::factory()->create([
            'email'    => 'exists@test.com',
            'password' => Hash::make('GoodPassword'),
        ]);

        // 2) Intentamos loguear con la contraseña equivocada
        $request = new Request([
            'loginInput' => 'exists@test.com',
            'password'   => 'WrongPassword',
        ]);

        $controller = new AuthController();
        $response   = $controller->login($request);

        // 3) Debe responder 401 y mensaje “Credenciales inválidas”
        $this->assertEquals(401, $response->getStatusCode());
        $this->assertEquals(
            'Credenciales inválidas',
            $response->getData(true)['message']
        );
    }

    public function test_it_validates_required_fields()
    {
        // 1) Envío request vacío, para forzar validación
        $request = new Request([]);

        $controller = new AuthController();
        $response   = $controller->login($request);

        // 2) Debe regresar 422 y en el JSON debe haber “errors”
        $this->assertEquals(422, $response->getStatusCode());
        $this->assertArrayHasKey('errors', $response->getData(true));
    }
}
