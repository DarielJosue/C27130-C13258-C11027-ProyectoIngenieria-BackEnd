<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\ValidatedData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Company;

class CompanyController extends Controller
{
    public function registerCompany(Request $request)
    {
        $companyUser = Auth::guard('s')->user();
        if (!$companyUser) {
            return response()->json(['message' => 'Usuario no autenticado'], 401);
        }
        if ($companyUser->company_id) {
            return response()->json(['message' => 'Ya tienes una empresa registrada'], 409);
        }
        $validatedData = $request->validate([
            'company_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'location' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'company_size' => 'nullable|integer',
            'specialties' => 'nullable|string|max:255',
            'register_date' => now(),
        ]);



        $company = Company::create($validatedData);
        $companyUser->company_id = $company->company_id;
        $companyUser->save();

        return response()->json([
            'message' => 'Creada con éxito',
            'company' => $company,
            'user' => $companyUser->fresh(),
        ], 201);
    }
}