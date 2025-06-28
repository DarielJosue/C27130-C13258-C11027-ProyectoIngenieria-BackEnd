<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JobPost;
use Illuminate\Support\Facades\Auth;
use App\Models\Application;
use Illuminate\Support\Facades\Log;
use App\Services\FirebaseService;

class ApplicationController extends Controller
{
    public function createApplication(Request $request)
    {
        try {
            $user = $request->user();

            $validatedData = $request->validate([
                'job_post_id' => 'required|exists:job_posts,job_post_id',
                'cv_id' => 'exists:curriculum,cv_id',
                'message' => 'nullable|string|max:1000',
            ]);
            if (!$user) {
                return response()->json(['message' => 'Usuario no autenticado'], 401);
            }

            $existingApplication = Application::where('user_id', $user->user_id)
                ->where('job_post_id', $validatedData['job_post_id'])
                ->first();
            if ($existingApplication) {
                return response()->json(['message' => 'Ya has aplicado a esta publicación'], 409);
            }

            $application = Application::create([
                'user_id' => $user->user_id,
                'job_post_id' => $validatedData['job_post_id'],
                'cv_id' => $validatedData['cv_id'],
                'application_date' => now(),
                'message' => $validatedData['message'] ?? null,
                'status' => 'Aplicando',
            ]);

            return response()->json([
                'message' => 'Aplicación creada con éxito',
                'application' => $application,
            ], 201);
        } catch (\Exception $e) {
            \Log::error('Error al aplicar al puesto', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'Error al crear la aplicación.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    // ApplicationController.php
    public function getApplicationsByUser(Request $request)
    {
        try {
            $user = $request->user(); // el usuario autenticado

            $applications = Application::with([
                'user',
                'curriculum',
                'jobPost.company'
            ])
                ->where('user_id', $user->user_id)
                ->latest()
                ->get();

            return response()->json($applications);
        } catch (\Exception $e) {
            \Log::error('Error al obtener las postulaciones del usuario', [
                'error' => $e->getMessage()
            ]);
            return response()->json([
                'message' => 'Error al obtener las postulaciones.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function getCompanyApplications($companyId)
    {
        try {
            $applications = Application::with(['user', 'curriculum', 'jobPost'])
                ->whereHas('jobPost', function ($query) use ($companyId) {
                    $query->where('company_id', $companyId);
                })
                ->orderBy('application_date', 'desc')
                ->get();

            return response()->json($applications);
        } catch (\Exception $e) {
            \Log::error('Error al obtener las aplicaciones de la empresa', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'Error al obtener las aplicaciones.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function applcationsByJobPost(JobPost $jobPost)
    {
        try {
            $companyUser = Auth::guard('api_company_users')->user();
            if ($companyUser || $jobPost->company_id !== $companyUser->company_id) {
                return response()->json(['message' => 'No autoizado para ver estas aplicaciones.'], 401);

            }

            $applications = Application::where('job_post_id', $jobPost->job_post_id)
                ->with(['user', 'curriculum'])
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json($applications);
        } catch (\Exception $e) {
            \Log::error('Error al obtener las aplicaciones por publicación de trabajo', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'Error al obtener las aplicaciones.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function updateStatus(Request $request, $application_id)
    {
        try {
            \Log::info('Inicio de actualización de estado', [
                'application_id' => $application_id,
                'payload' => $request->all()
            ]);

            $companyUser = $request->user();

            if (!$companyUser) {
                \Log::warning('Usuario no autenticado');
                return response()->json(['message' => 'No autorizado'], 401);
            }

            $application = Application::with(['jobPost'])->find($application_id);

            if (!$application) {
                \Log::error('Aplicación no encontrada', ['id' => $application_id]);
                return response()->json(['message' => 'Aplicación no encontrada'], 404);
            }

            \Log::debug('Aplicación encontrada', [
                'job_post_id' => $application->job_post_id,
                'current_status' => $application->status
            ]);

            if (!$application->jobPost) {
                \Log::error('JobPost no asociado', [
                    'application_id' => $application_id,
                    'job_post_id' => $application->job_post_id
                ]);
                return response()->json(['message' => 'JobPost no encontrado'], 404);
            }

            if ($application->jobPost->company_id != $companyUser->company_id) {
                \Log::warning('Acceso no autorizado', [
                    'user_company' => $companyUser->company_id,
                    'jobpost_company' => $application->jobPost->company_id
                ]);
                return response()->json(['message' => 'No autorizado'], 401);
            }

            $validatedData = $request->validate([
                'status' => 'required|string|in:Aceptado,Revisado,Rechazado,Aplicado',
            ]);

            $application->status = $validatedData['status'];
            $application->save();

            \Log::info('Estado actualizado exitosamente', [
                'application_id' => $application_id,
                'new_status' => $application->status
            ]);


            \Log::info('Enviando notificación de aceptación', [
                'user_id' => $application->user_id,
                'application_id' => $application->application_id
            ]);
            $notificationController = new NotificationController();
            $notificationController->notifyApplicationChange($application);

            return response()->json([
                'message' => 'Estado actualizado con éxito',
                'application' => $application,
            ]);

        } catch (\Illuminate\Validation\ValidationException $ve) {
            \Log::error('Error de validación', [
                'errors' => $ve->errors(),
                'input' => $request->all()
            ]);
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $ve->errors()
            ], 422);

        } catch (\Exception $e) {
            \Log::error('Error crítico', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'message' => 'Error interno del servidor',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}