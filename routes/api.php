<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\JobPostController;
use App\Http\Controllers\Api\ApplicationController;
use App\Http\Controllers\Api\CurriculumController;
use App\Http\Controllers\Api\ProfileController;

Route::post('logout', [AuthController::class, 'logout']);
Route::post('register', [AuthController::class, 'register']);
Route::post('register-company-user', [AuthController::class, 'registerCompanyUser']);
Route::post('login', [AuthController::class, 'login']);
Route::get('me', [AuthController::class, 'userData'])->middleware('auth:sanctum');


Route::prefix('company')
    ->middleware(['auth:sanctum', 'ability:jobpost:create,company:create,company:view'])
    ->group(function () {
        Route::post('create-post', [JobPostController::class, 'createJobPost']);
        Route::put('update-job-posts/{job_post}', [JobPostController::class, 'update']);
        Route::delete('delete-job-posts/{job_post}', [JobPostController::class, 'delete']);
        Route::get('company-job-posts', [JobPostController::class, 'companyJobPosts']);
        Route::get('applications/by-company', [ApplicationController::class, 'getApplicationsByUser']);
        Route::post('registerCompany', [CompanyController::class, 'registerCompany']);
Route::middleware('auth:sanctum')->get('company/by-user', [CompanyController::class, 'getCompanyByUser']);
    });
Route::get('job-posts', [JobPostController::class, 'index'])
    ->middleware('auth:sanctum');


Route::middleware('auth:sanctum')->group(function () {
    Route::post('job-posts/apply', [ApplicationController::class, 'createApplication']);
    Route::get('job-posts/by-company/{id}', [JobPostController::class, 'getJobPostById']);
    Route::post('/job-posts/{id}/save', [JobPostController::class, 'save']);
});

Route::prefix('curriculum')
    ->middleware('auth:sanctum')->group(function () {
        Route::get('getCurriculum', [CurriculumController::class, 'getCurriculum']);
        Route::post('upload', [CurriculumController::class, 'saveCurriculum']);
        Route::put('update', [CurriculumController::class, 'updateCurriculum']);
        Route::delete('delete', [CurriculumController::class, 'deleteCurriculum']);

    });
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/job-posts/{job_post}/applications', [ApplicationController::class, 'applcationsByJobPost']);
    Route::put('/applications/{application_id}/status', [ApplicationController::class, 'updateStatus']);
    Route::get('/applications/by-company/{companyId}', [ApplicationController::class, 'getCompanyApplications']);
});

Route::prefix('profile')
    ->middleware('auth:sanctum')->group(function () {
        Route::get('/', [ProfileController::class, 'index']);
        Route::post('/create', [ProfileController::class, 'create']);
        Route::post('/upload-profile-picture', [ProfileController::class, 'uploadProfilePicture']);
        Route::put('/update-profile-picture', [ProfileController::class, 'updateProfilePicture']);
    });


Route::get('user', [UserController::class, 'index']);
Route::middleware('auth:sanctum')->post('/messages/send', [ChatController::class, 'sendMessage']);
Route::middleware('auth:sanctum')->get('/messages/{recipientId}', [ChatController::class, 'getMessages']);
Route::get('/test', fn() => response()->json(['message' => 'API funciona']));