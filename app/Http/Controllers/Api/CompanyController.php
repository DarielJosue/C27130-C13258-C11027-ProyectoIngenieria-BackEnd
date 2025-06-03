<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\CompanyUser;
use Illuminate\Support\Facades\Log;

class CompanyController extends Controller
{
    public function registerCompany(Request $request)
    {
        try {
            \Log::error('Entrando a registerCompany', ['user' => $request->user()]);
            $companyUser = $request->user();
            if (!$companyUser) {
                return response()->json(['message' => 'Usuario no autenticado'], 401);
            }
            if ($companyUser->company_id) {
                return response()->json(['message' => 'Ya tienes una empresa registrada'], 409);
            }
            $validatedData = $request->validate([
                'company_name' => 'string|max:255',
                'description' => 'nullable|string',
                'phone' => 'nullable|string|max:20',
                'location' => 'nullable|string|max:255',
                'website' => 'nullable|url|max:255',
                'company_size' => 'nullable|string|max:50',
                'specialties' => 'nullable|string|max:255',
            ]);
            $validatedData['register_date'] = now();

            $company = Company::create($validatedData);
            $companyUser->company_id = $company->company_id;
            $companyUser->save();

            return response()->json([
                'message' => 'Creada con éxito',
                'company_id' => $company->company_id,
            ], 201);
        } catch (\Exception $e) {
            \Log::error('Error al registrar la empresa: ' . $e->getMessage(), [
                'user_id' => $request->user()->id,
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }

    }
    public function getCompanyByUser(Request $request)
    {
        try {
            $companyUser = $request->user();
            if (!$companyUser) {
                return response()->json(['message' => 'Usuario no autenticado'], 401);
            }


            $company = Company::find($companyUser->company_id);
            if (!$company) {
                return response()->json(['message' => 'Empresa no encontrada'], 404);
            }

            return response()->json($company, 200);
        } catch (\Exception $e) {
            \Log::error('Error al encontrar la empresa del usuario: ' . $e->getMessage(), [
                'user_id' => $request->user()->id,
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }
}