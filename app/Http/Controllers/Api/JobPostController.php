<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JobPost;
use Illuminate\Support\Facades\Auth;


class JobPostController extends Controller
{
    public function index(Request $request)
    {
        $paginatedPosts = JobPost::with('company')
            ->orderBy('publish_date', 'desc')
            ->paginate(15);



        $mappedPosts = $paginatedPosts->getCollection()->transform(function ($post) {
            return [
                'id' => $post->job_post_id,
                'title' => $post->title,
                'description' => $post->description,
                'requirements' => $post->requirements,
                'salary' => $post->salary,
                'location' => $post->location,
                'company_name' => $post->company->company_name ?? 'Empresa no disponible',
                'created_at' => $post->publish_date,
            ];
        });



        $paginatedPosts->setCollection($mappedPosts);
        return response()->json($paginatedPosts);
    }
    public function getJobPostById($id)
    {
        $jobPost = JobPost::with('company')->find($id);
        if (!$jobPost) {
            return response()->json(['message' => 'Publicación de trabajo no encontrada'], 404);
        }

        return response()->json([
            'id' => $jobPost->job_post_id,
            'title' => $jobPost->title,
            'description' => $jobPost->description,
            'requirements' => $jobPost->requirements,
            'salary' => $jobPost->salary,
            'location' => $jobPost->location,
            'company_name' => $jobPost->company->company_name ?? 'Empresa no disponible',
            'created_at' => $jobPost->publish_date,
        ]);
    }
    public function apply($id)
    {
        try {
            $user = auth()->user();

            if (!$user) {
                return response()->json(['message' => 'Usuario no autenticado'], 401);
            }
            $jobPost = JobPost::find($id);
            $userId = $user ? $user->user_id : null;

            return response()->json(['message' => 'Aplicación recibida']);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al iniciar sesión',
                'error' => $e->getMessage()
            ], 500);
        }

    }
    public function save($id)
    {
        $jobPost = JobPost::find($id)->first();
        $user = auth()->user();
        return response()->json(['message' => 'Oferta guardada']);
    }
    public function getCompanyJobPosts($companyId)
    {
        $jobPosts = JobPost::where('company_id', $companyId)
            ->with('company')
            ->orderBy('publish_date', 'desc')
            ->paginate(15);

        return response()->json($jobPosts);
    }
    public function createJobPost(Request $request)
    {
        try {

            $companyUser = $request->user();
            if (!$companyUser) {
                return response()->json(['message' => 'Usuario no autenticado'], 401);
            }
            if (!$companyUser->company_id) {
                return response()->json(['message' => 'No tienes una empresa registrada'], 409);
            }

            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'location' => 'nullable|string|max:255',
                'salary' => 'nullable|numeric',
                'requirements' => 'nullable|string|max:255',
            ]);
            $validatedData['company_id'] = $companyUser->company_id;
            $validatedData['publish_date'] = now();
            $validatedData['active'] = true;

            $jobPost = $companyUser->company->jobPosts()->create($validatedData);

            return response()->json([
                'message' => 'Publicación de trabajo creada con éxito',
                'job_post' => $jobPost,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }
    public function update(Request $request, JobPost $jobPost)
    {
        $companyUser = Auth::guard('api_company_users')->user();
        if (!$companyUser || $jobPost->company_id !== $companyUser->company_id) {
            return response()->json(['message' => 'No autorizado para editar esta publicación.'], 401);
        }

        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'nullable|string|max:255',
            'salary' => 'nullable|numeric',
            'requirements' => 'nullable|string|max:255',
        ]);

        $jobPost->update($validatedData);

        return response()->json([
            'message' => 'Publicación de trabajo actualizada con éxito',
            'job_post' => $jobPost,
        ]);

    }
    public function delete(JobPost $jobPost)
    {
        $companyUser = Auth::guard('api_company_users')->user();
        if (!$companyUser || $jobPost->company_id !== $companyUser->company_id) {
            return response()->json(['message' => 'No autorizado para eliminar esta publicación.'], 401);
        }
        $jobPost->delete();

        return response()->json(['message' => 'Publicación de trabajo eliminada con éxito']);
    }

    public function companyJobPosts()
    {
        $companyUser = Auth::guard('api_company_users')->user();
        if (!$companyUser) {
            return response()->json(['message' => 'No autorizado.'], 403);
        }
        $jobPosts = JobPost::where('company_id', $companyUser->company_id)
            ->with('company')
            ->orderBy('publish_date', 'desc')
            ->paginate(15);
        return response()->json($jobPosts);
    }
}