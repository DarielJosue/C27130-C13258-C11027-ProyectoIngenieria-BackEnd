<?php



use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\UserController;

Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout']);
Route::post('register', [AuthController::class, 'register']);
Route::get('user', [UserController::class, 'index']);
Route::post('/messages/send', [ChatController::class, 'sendMessage']);
Route::get('/messages/{recipientId}', [ChatController::class, 'getMessages']);
Route::get('test', fn() => response()->json(['message' => 'API funciona']));