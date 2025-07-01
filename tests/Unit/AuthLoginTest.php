<?php

namespace Tests\Unit;

use Tests\TestCase;                    
use App\Models\User;
use App\Models\CompanyUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Controllers\Api\AuthController;

class AuthLoginTest extends TestCase      
{
    use RefreshDatabase;                

    public function test_it_logs_in_applicant_successfully()
    {
        $user = User::factory()->create([
            'email'    => 'test@example.com',
            'password' => Hash::make('Password123'),
        ]);

        $request = new Request([
            'loginInput' => 'test@example.com',
            'password'   => 'Password123',
        ]);

        $controller = new AuthController();
        $response   = $controller->login($request);

        $this->assertEquals(200, $response->getStatusCode());
        $responseData = $response->getData(true);
        $this->assertEquals('Inicio de sesión exitoso', $responseData['message']);
        $this->assertArrayHasKey('token', $responseData);
    }

    public function test_it_logs_in_company_user_successfully()
    {
        $companyUser = CompanyUser::factory()->create([
            'email'    => 'company@test.com',
            'password' => Hash::make('CompanyPass123'),
        ]);

        $request = new Request([
            'loginInput' => 'company@test.com',
            'password'   => 'CompanyPass123',
        ]);

        $controller   = new AuthController();
        $response     = $controller->login($request);
        $responseData = $response->getData(true);

        $this->assertEquals('company', $responseData['user_type']);
        $this->assertArrayHasKey('company_id', $responseData);
    }

    public function test_it_rejects_invalid_credentials()
    {
        User::factory()->create([
            'email'    => 'exists@test.com',
            'password' => Hash::make('GoodPassword'),
        ]);

        $request = new Request([
            'loginInput' => 'exists@test.com',
            'password'   => 'WrongPassword',
        ]);

        $controller = new AuthController();
        $response   = $controller->login($request);

        $this->assertEquals(401, $response->getStatusCode());
        $this->assertEquals(
            'Credenciales inválidas',
            $response->getData(true)['message']
        );
    }

    public function test_it_validates_required_fields()
    {
        $request = new Request([]);

        $controller = new AuthController();
        $response   = $controller->login($request);

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertArrayHasKey('errors', $response->getData(true));
    }
}
