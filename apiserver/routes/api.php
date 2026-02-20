<?php

use App\Http\Controllers\Api\ActivityController;
use App\Http\Controllers\Api\AdsController;
use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\ApiSpaceController;
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
use App\Http\Controllers\Api\Internal\SaasBridgeController;
use App\Http\Controllers\Api\Internal\SaasProjectController as InternalSaasProjectController;
use App\Http\Controllers\Api\PageDataController;
use App\Http\Controllers\Api\LicenseController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\ProductReviewController;
use App\Http\Controllers\Api\SaasProjectController;
use App\Http\Controllers\Api\SaasSiteController;
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

// Backward-compatible auth routes kept for legacy clients and existing tests.
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

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

Route::prefix('internal/saas')->group(function () {
    Route::post('/site-stats', [SaasBridgeController::class, 'ingestSiteStats']);
    Route::post('/project/heartbeat', [InternalSaasProjectController::class, 'heartbeat']);
    Route::post('/license/check', [SaasBridgeController::class, 'checkLicense']);
    Route::get('/insights/site/{siteUuid}', [SaasBridgeController::class, 'siteInsights']);
    Route::get('/insights/vendor/{vendorId}', [SaasBridgeController::class, 'vendorInsights']);
    Route::get('/insights/overview', [SaasBridgeController::class, 'overviewInsights']);
    Route::get('/plan/resolve', [SaasBridgeController::class, 'resolvePlan']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::get('/licenses', [LicenseController::class, 'index']);
    Route::get('/licenses/summary', [LicenseController::class, 'summary']);
    Route::post('/licenses/subscribe', [LicenseController::class, 'subscribe']);
    Route::get('/licenses/{licenseUuid}', [LicenseController::class, 'show']);
    Route::put('/licenses/{licenseUuid}/api-key', [LicenseController::class, 'updateApiKey']);
    Route::post('/licenses/{licenseUuid}/api-key/regenerate', [LicenseController::class, 'regenerateApiKey']);
    Route::post('/licenses/{licenseUuid}/renew', [LicenseController::class, 'renew']);
    Route::post('/licenses/{licenseUuid}/regenerate-license', [LicenseController::class, 'regenerateLicense']);
    Route::post('/licenses/{licenseUuid}/upgrade', [LicenseController::class, 'upgrade']);
    Route::get('/saas/sites', [SaasSiteController::class, 'index']);
    Route::post('/saas/licenses/{license}/sites', [SaasSiteController::class, 'create']);
    Route::get('/saas/sites/{siteUuid}', [SaasSiteController::class, 'show']);
    Route::get('/saas/insights/overview', [SaasSiteController::class, 'overview']);
    Route::get('/saas/insights/projects/{project}', [SaasSiteController::class, 'projectInsights']);
    Route::get('/saas/insights/vendors/{vendorId}', [SaasSiteController::class, 'vendorInsights']);
    Route::get('/saas/projects', [SaasProjectController::class, 'index']);
    Route::get('/saas/projects/{project}', [SaasProjectController::class, 'show']);
    Route::post('/saas/projects/{project}/ping', [SaasProjectController::class, 'ping']);
    Route::get('/api-spaces', [ApiSpaceController::class, 'index']);
    Route::post('/api-spaces', [ApiSpaceController::class, 'store']);
    Route::get('/api-spaces/{spaceUuid}', [ApiSpaceController::class, 'show']);
    Route::put('/api-spaces/{apiSpace}', [ApiSpaceController::class, 'update']);
    Route::delete('/api-spaces/{spaceUuid}', [ApiSpaceController::class, 'destroy']);
    Route::get('/reviews/my', [ProductReviewController::class, 'my']);
    Route::post('/reviews', [ProductReviewController::class, 'upsert']);
    Route::post('/logout', [AuthController::class, 'logout']);
});
