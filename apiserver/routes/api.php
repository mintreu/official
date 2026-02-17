<?php

use App\Http\Controllers\Api\ActivityController;
use App\Http\Controllers\Api\AdsController;
use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\OtpController;
use App\Http\Controllers\Api\Auth\PasswordResetController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\CaseStudyController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\DownloadController;
use App\Http\Controllers\Api\HealthController;
use App\Http\Controllers\Api\PageDataController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProjectController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/health', HealthController::class);
Route::get('/home', [PageDataController::class, 'home']);

Route::prefix('auth')->group(function () {
    Route::post('/register', [RegisterController::class, 'register']);
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/logout', [LoginController::class, 'logout']);
    Route::post('/otp/send', [OtpController::class, 'send']);
    Route::post('/otp/verify', [OtpController::class, 'verify']);
    Route::post('/password/forgot', [PasswordResetController::class, 'forgotPassword']);
    Route::post('/password/reset', [PasswordResetController::class, 'resetPassword']);
});

Route::get('/projects', [ProjectController::class, 'index']);
Route::get('/projects/{slug}', [ProjectController::class, 'show']);

Route::get('/case-studies', [CaseStudyController::class, 'index']);
Route::get('/case-studies/{slug}', [CaseStudyController::class, 'show']);

Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{slug}', [ProductController::class, 'show']);
Route::get('/products/{slug}/sources', [DownloadController::class, 'sources']);
Route::post('/products/{slug}/download', [DownloadController::class, 'initiate']);

Route::get('/ads', [AdsController::class, 'index']);

Route::get('/download/{token}', [DownloadController::class, 'download'])->name('api.download');

Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/articles', [ArticleController::class, 'index']);
Route::get('/articles/{slug}', [ArticleController::class, 'show']);

Route::post('/contact', [ContactController::class, 'store']);

Route::post('/activities/share', [ActivityController::class, 'recordShare']);
Route::post('/activities/view', [ActivityController::class, 'recordView']);
Route::post('/activities/download', [ActivityController::class, 'recordDownload']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::get('/licenses', [LicenseController::class, 'index']);
    Route::post('/logout', [AuthController::class, 'logout']);
});
