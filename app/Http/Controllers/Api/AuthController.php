<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\CompanyUser;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    /**
     * Login del usuario (API)
     */
    public function login(Request $request)
    {
        try {
            $credentials = $request->only('loginInput', 'password');

            $request->validate([
                'loginInput' => 'required|string',
                'password' => 'required|string|min:8',
            ]);

            $loginInput = $credentials['loginInput'];
            $password = $credentials['password'];


            $field = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
            $user = User::where($field, $loginInput)->first();
            $userType = 'applicant';

            if (!$user) {
                $user = CompanyUser::where($field, $loginInput)->first();
                $userType = 'company';
            }


            if (!$user || !Hash::check($password, $user->password)) {
                return response()->json(['message' => 'Credenciales inválidas'], 401);
            }

            $abilities = $userType === 'company' ?
                ['jobpost:create', 'jobpost:view', 'jobpost:update', 'jobpost:delete'] :
                ['user:actions'];

            $token = $user->createToken('auth_token', $abilities)->plainTextToken;


            if ($userType === 'company') {
                return response()->json([
                    'message' => 'Inicio de sesión exitoso company',
                    'user' => $user,
                    'token' => $token,
                    'user_type' => $userType,
                    'token_type' => 'Bearer',
                    'company_id' => $user->company_id,
                    'abilities' => $abilities,
                ]);
            } else {
                return response()->json([
                    'message' => 'Inicio de sesión exitoso',
                    'user' => $user,
                    'token' => $token,
                    'user_type' => $userType,
                    'token_type' => 'Bearer',
                    'company_id' => null,
                ]);
            }
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Error en la validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al iniciar sesión',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function userData(Request $request)
    {
        try {
            $user = $request->user();
            if ($user instanceof User) {
                return response()->json([
                    'name' => $user->name,
                    'lastname' => $user->lastname,
                    'username' => $user->username,
                    'email' => $user->email,
                ]);
            } elseif ($user instanceof CompanyUser) {
                return response()->json([
                    'name' => $user->name,
                    'lastname' => $user->lastname,
                    'username' => $user->username,
                    'email' => $user->email,
                ]);
            } else {
                return response()->json(['message' => 'Usuario no encontrado'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al obtener los datos del usuario', 'error' => $e->getMessage()], 500);
        }
    }

    public function register(Request $request)
    {
        try {

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'lastname' => 'required|string|max:255',
                'username' => 'required|string |unique:users|max:30',
                'email' => 'required|email|unique:users|max:255',
                'password' => 'required|string|min:8|confirmed'
            ]);


            $user = User::create([
                'name' => $validated['name'],
                'lastname' => $validated['lastname'],
                'username' => $validated['username'],
                'email' => $validated['email'],
                'password' => bcrypt($validated['password']),
                'registration_date' => now(),
            ]);


            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'user' => $user,
                'token' => $token,
                'token_type' => 'Bearer'
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Error ',
                'errors' => $e->errors()
            ], 422);
        }
    }

    public function registerCompanyUser(Request $request)
    {
        try {

            $validated = $request->validate([
                'company_id' => 'nullable|integer|exists:companies,id',
                'name' => 'required|string|max:255',
                'lastname' => 'required|string|max:255',
                'username' => 'required|string|unique:company_users|max:30',
                'email' => 'required|email|unique:company_users|max:255',
                'password' => 'required|string|min:8|confirmed',
                'role' => 'nullable|string|max:50',
            ]);

            $companyUser = CompanyUser::create([
                'company_id' => $validated['company_id'],
                'name' => $validated['name'],
                'lastname' => $validated['lastname'],
                'username' => $validated['username'],
                'email' => $validated['email'],
                'password' => bcrypt($validated['password']),
                'role' => $validated['role'] ?? 'default_role',
                'active' => true,
                'register_date' => now(),
            ]);
            $token = $companyUser->createToken('auth_token')->plainTextToken;
            return response()->json([
                'user' => $companyUser,
                'token' => $token,
                'token_type' => 'Bearer'
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Error ',
                $e->getMessage(),
                'errors' => $e->errors()
            ], 422);
        }
    }
    /**
     * Cierre de sesión (Revocar token)
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Sesión cerrada correctamente',
        ]);
    }

}