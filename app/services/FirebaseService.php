<?php
namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class FirebaseService
{
    protected $messaging;
    public function __construct()
    {
        $factory = (new Factory)->withServiceAccount(config('services.firebase.credentials'));
        $this->messaging = $factory->createMessaging();
    }

    public function sendNotificationToDevice(string $token, string $title, string $body, array $data = [], ?int $user_id = null)
    {
        \Log::info('[Firebase] Enviando push. user_id:', [$user_id]);
        $message = CloudMessage::withTarget('token', $token)
            ->withNotification(Notification::create($title, $body))
            ->withData($data);

        try {
            $this->messaging->send($message);
            \Log::info('[Firebase] Notificación enviada a token: ' . $token);
        } catch (\Throwable $e) {
            \Log::error('[Firebase Error] user_id: ' . ($user_id ?? 'N/A') . ' | ' . $e->getMessage());
        }
    }

}