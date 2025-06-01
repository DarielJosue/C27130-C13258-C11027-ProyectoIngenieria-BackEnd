<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CurriculumController extends Controller
{
    /**
     * Get the authenticated user's curriculum.
     */
    public function getCurriculum(Request $request)
    {
        try {
            $user = $request->user();

            if (!$user) {
                return response()->json(['message' => 'Usuario no autenticado'], 401);
            }

            $curriculum = $user->curriculum;

            if (!$curriculum) {
                return response()->json(['message' => 'Currículum no encontrado'], 404);
            }

            return response()->json($curriculum);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al obtener el currículum.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function saveCurriculum(Request $request)
    {
        try {
            $user = $request->user();

            if (!$user) {
                return response()->json(['message' => 'Usuario no autenticado'], 401);
            }

            $data = $request->validate([
                'file' => 'required|file|mimes:pdf|max:2048',
                'description' => 'nullable|string|max:500',
            ]);

            $path = $request->file('file')->store('curriculums', 'public');


            $curriculum = $user->curriculum()->create([
                'file_path' => $path,
                'description' => $data['description'] ?? null,
                'upload_date' => now(),
                'is_default' => true,
            ]);

            return response()->json([
                'message' => 'Currículum subido correctamente.',
                'curriculum' => $curriculum
            ], 200);

        } catch (\Exception $e) {
            \Log::error('Error al guardar currículum', [
                'error' => $e->getMessage(),
            ]);
            return response()->json([
                'message' => 'Error al guardar el currículum.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function updateCurriculum(Request $request)
    {
        try {
            $user = $request->user();

            if (!$user) {
                return response()->json(['message' => 'Usuario no autenticado'], 401);
            }

            $curriculum = $user->curriculum;

            if (!$curriculum) {
                return response()->json(['message' => 'Currículum no encontrado'], 404);
            }

            $data = $request->validate([
                'file_path' => 'required|string|max:255',
                'description' => 'nullable|string|max:500',
            ]);
            $data['upload_date'] = now();
            $data['is_default'] = true;

            $curriculum->update($data);

            return response()->json($curriculum, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar el currículum.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function deleteCurriculum(Request $request)
    {
        try {
            $user = $request->user();

            if (!$user) {
                return response()->json(['message' => 'Usuario no autenticado'], 401);
            }

            $curriculum = $user->curriculum;

            if (!$curriculum) {
                return response()->json(['message' => 'Currículum no encontrado'], 404);
            }

            $curriculum->delete();

            return response()->json(['message' => 'Currículum eliminado correctamente'], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al eliminar el currículum.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function getDefaultCurriculum(Request $request)
    {
        try {
            $user = $request->user();

            if (!$user) {
                return response()->json(['message' => 'Usuario no autenticado'], 401);
            }

            $curriculum = $user->curriculum()->where('is_default', true)->first();

            if (!$curriculum) {
                return response()->json(['message' => 'Currículum por defecto no encontrado'], 404);
            }

            return response()->json($curriculum);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al obtener el currículum por defecto.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function setDefaultCurriculum(Request $request)
    {
        try {
            $user = $request->user();

            if (!$user) {
                return response()->json(['message' => 'Usuario no autenticado'], 401);
            }

            $curriculum = $user->curriculum;

            if (!$curriculum) {
                return response()->json(['message' => 'Currículum no encontrado'], 404);
            }


            $user->curriculum()->update(['is_default' => false]);

            $curriculum->is_default = true;
            $curriculum->save();

            return response()->json($curriculum, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al establecer el currículum por defecto.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}