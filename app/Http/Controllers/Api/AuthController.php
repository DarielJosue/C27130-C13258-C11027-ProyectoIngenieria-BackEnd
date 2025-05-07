<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    /**
     * Login del usuario (API)
     */
    public function login(Request $request)
    {
        $credentials = $request->only('loginInput', 'password');


        $loginField = filter_var($credentials['loginInput'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';


        if (Auth::attempt([$loginField => $credentials['loginInput'], 'password' => $credentials['password']])) {
            $user = Auth::user();

            return response()->json([
                'message' => 'Inicio de sesión exitoso',
                'user' => $user,
                'token' => $user->createToken('token-name')->plainTextToken,
            ]);
        }

        return response()->json(['message' => 'credenciales inválidos'], 401);
    }

    public function register(Request $request)
    {
        try {

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'lastname' => 'required|string|max:255',
                'username' => 'required|string|unique:users|max:30',
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