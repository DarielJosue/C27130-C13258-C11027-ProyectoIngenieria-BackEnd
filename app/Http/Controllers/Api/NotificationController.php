<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\User;
use App\Models\JobPost;
use App\Services\FirebaseService;
use App\Models\Company;

class NotificationController extends Controller
{
    public function notifyApplicationChange(Application $application)
    {
        try {
            $user = User::find($application->user_id);
            $jobPost = JobPost::find($application->job_post_id);
            $company = Company::find($jobPost->company_id);
            \Log::info('notifyApplicationChange - user_id:', [$user->user_id ?? 'null']);

            $estado = strtolower($application->status);
            $mensaje = match ($estado) {
                'aceptado' =>
                '¡Tu postulación a la empresa ' . ($company->company_name ?? '') .
                ' en el puesto ' . ($jobPost->title ?? '') .
                ' ha sido aceptada!',
                'rechazado' =>
                'Tu postulación a la empresa ' . ($company->company_name ?? '') .
                ' en el puesto ' . ($jobPost->title ?? '') .
                ' fue rechazada.',
                default =>
                'Tu postulación a la empresa ' . ($company->company_name ?? '') .
                ' en el puesto ' . ($jobPost->title ?? '') .
                ' ha sido actualizada.',
            };




            $user->notifications()->create([
                'title' => 'Cambio en tu postulación',
                'body' => $mensaje,
                'data' => ['application_id' => $application->application_id],
            ]);


            $firebase = new FirebaseService();
            foreach ($user->deviceTokens as $device) {
                \Log::info('Tokens encontrados:', $user->deviceTokens->pluck('token')->toArray());
                \Log::info('Payload de notificación:', [
                    'title' => 'Cambio en tu postulación',
                    'body' => $mensaje,
                    'data' => ['application_id' => $application->application_id],
                ]);

                \Log::info('Enviando push a user_id: ' . $user->user_id . ', token: ' . $device->token);
                $firebase->sendNotificationToDevice(
                    $device->token,
                    'Cambio en tu postulación',
                    $mensaje,
                    ['application_id' => $application->application_id],
                    $user->user_id
                );
            }

            return response()->json(['message' => 'Notificación enviada']);
        } catch (\Throwable $e) {
            \Log::error('Error en notifyApplicationChange: ' . $e->getMessage());
            return response()->json(['message' => 'Error al enviar notificación'], 500);
        }
    }


    public function testPushNotification(Request $request)
    {
        \Log::info('Llego al endpoint testPushNotification');
        \Log::info('Request data:', $request->all());
        $request->validate([
            'user_id' => 'required|integer|exists:users,user_id',
            'token' => 'required|string',
            'title' => 'required|string',
            'body' => 'required|string',
            // 'data' => 'sometimes|array',
        ]);

        $user = User::find($request->user_id);
        \Log::info('user encontrado:', [$user?->user_id ?? 'null']);

        try {
            $firebase = new FirebaseService();
            $firebase->sendNotificationToDevice(
                $request->token,
                $request->title,
                $request->body,
                $request->input('data', []),
                $user->user_id
            );
            return response()->json(['success' => true, 'message' => 'Notificación enviada correctamente.']);
        } catch (\Throwable $e) {
            \Log::error('Error en testPushNotification: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al enviar la notificación.',
                'error' => $e->getMessage(),
            ], 500);

        }
    }

    public function storeDeviceToken(Request $request)
    {
        $request->validate([
            'token' => 'required|string'
        ]);

        $user = $request->user();

        $user->deviceTokens()->updateOrCreate(
            ['token' => $request->token],
            []
        );

        return response()->json(['message' => 'Token guardado']);
    }
    public function getUserNotifications(Request $request)
    {
        $notificaciones = $request->user()->notifications()->latest()->get();

        return response()->json($notificaciones);
    }

}