<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\CaseStudyController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\HealthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\PageDataController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/health', HealthController::class);
Route::get('/home', [PageDataController::class, 'home']);

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/projects', [ProjectController::class, 'index']);
Route::get('/projects/{slug}', [ProjectController::class, 'show']);

Route::get('/case-studies', [CaseStudyController::class, 'index']);
Route::get('/case-studies/{slug}', [CaseStudyController::class, 'show']);

Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{slug}', [ProductController::class, 'show']);

Route::get('/articles', [ArticleController::class, 'index']);
Route::get('/articles/{slug}', [ArticleController::class, 'show']);

// Contact form submissions (public)
Route::post('/contact', [ContactController::class, 'store']);

// Admin contact management (protected)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/admin/contacts', [ContactController::class, 'index']);
    Route::get('/admin/contacts/stats', [ContactController::class, 'stats']);
    Route::get('/admin/contacts/{contact}', [ContactController::class, 'show']);
    Route::put('/admin/contacts/{contact}', [ContactController::class, 'update']);
    Route::delete('/admin/contacts/{contact}', [ContactController::class, 'destroy']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);
});
