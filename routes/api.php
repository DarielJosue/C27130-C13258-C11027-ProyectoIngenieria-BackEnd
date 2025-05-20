<?php



use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\JobPostController;
use App\Http\Controllers\Api\ApplicationController;

Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout']);
Route::post('register', [AuthController::class, 'register']);
Route::post('register-company-user', [AuthController::class, 'registerCompanyUser']);

Route::apiResource('job-posts', JobPostController::class)->except(['createJobPost', 'update', 'delete']); 

Route::middleware(['auth:sanctum', 'abilities:auth_token'])->group(function () {
    Route::post('create-post', [JobPostController::class, 'createJobPost']);
    Route::put('job-posts/{job_post}', [JobPostController::class, 'update']);
    Route::delete('job-posts/{job_post}', [JobPostController::class, 'delete']);
    Route::get('company-job-posts', [JobPostController::class, 'companyJobPosts']);
});


Route::middleware(['auth:sanctum', 'abilities:auth_token'])->group(function () { 
    Route::post('job-posts/{job_post}/apply', [ApplicationController::class, 'createApplication']);
    Route::get('applications', [ApplicationController::class, 'getApplicationsByUser']);
});


Route::middleware(['auth:sanctum', 'abilities:company-token'])->group(function () { 
    Route::get('job-posts/{job_post}/applications', [ApplicationController::class, 'applcationsByJobPost']);
    Route::put('applications/{application}/status', [ApplicationController::class, 'updateStatusForApplcation']);
});

Route::middleware('auth:sanctum')->post('register-company', [CompanyController::class, 'registerCompany']);
Route::get('user', [UserController::class, 'index']);
Route::middleware('auth:sanctum')->post('/messages/send', [ChatController::class, 'sendMessage']);
Route::middleware('auth:sanctum')->get('/messages/{recipientId}', [ChatController::class, 'getMessages']);
Route::get('/test', fn() => response()->json(['message' => 'API funciona']));