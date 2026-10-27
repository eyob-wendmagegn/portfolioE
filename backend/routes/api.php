<?php

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\ContactMessageController as AdminContactMessageController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\EducationController as AdminEducationController;
use App\Http\Controllers\Admin\LibraryDashboardController as AdminLibraryDashboardController;
use App\Http\Controllers\Admin\ProjectController as AdminProjectController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\Admin\SkillController as AdminSkillController;
use App\Http\Controllers\Admin\TechnologyController as AdminTechnologyController;
use App\Http\Controllers\PublicPortfolioController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes - Version 1 (v1)
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {

    // -------------------------------------------------------------
    // Public Portfolio Endpoints (No Authentication Required)
    // -------------------------------------------------------------
    Route::get('/portfolio', [PublicPortfolioController::class, 'index']);
    Route::get('/projects', [PublicPortfolioController::class, 'projects']);
    Route::get('/projects/{slug}', [PublicPortfolioController::class, 'project']);
    Route::get('/skills', [PublicPortfolioController::class, 'skills']);
    Route::get('/services', [PublicPortfolioController::class, 'services']);
    Route::get('/library-dashboards', [PublicPortfolioController::class, 'libraryDashboards']);
    Route::get('/site-settings', [PublicPortfolioController::class, 'siteSettings']);
    Route::post('/contact', [PublicPortfolioController::class, 'contact'])->middleware('throttle:10,1');
    Route::get('/cv/download', [PublicPortfolioController::class, 'downloadCv']);

    // -------------------------------------------------------------
    // Admin Authentication (Public with Throttle)
    // -------------------------------------------------------------
    Route::post('/admin/login', [AdminAuthController::class, 'login'])->middleware('throttle:5,1');

    // -------------------------------------------------------------
    // Protected Admin Routes (Sanctum + Admin Middleware)
    // -------------------------------------------------------------
    Route::prefix('admin')->middleware(['auth:sanctum', 'admin'])->group(function () {
        // Auth session
        Route::post('/logout', [AdminAuthController::class, 'logout']);
        Route::get('/me', [AdminAuthController::class, 'me']);
        Route::put('/profile', [AdminAuthController::class, 'updateProfile']);

        // Dashboard statistics
        Route::get('/dashboard/stats', [AdminDashboardController::class, 'stats']);

        // Projects management
        Route::get('/projects', [AdminProjectController::class, 'index']);
        Route::post('/projects', [AdminProjectController::class, 'store']);
        Route::get('/projects/{id}', [AdminProjectController::class, 'show']);
        Route::put('/projects/{id}', [AdminProjectController::class, 'update']);
        Route::post('/projects/{id}', [AdminProjectController::class, 'update']); // for multipart/form-data support
        Route::delete('/projects/{id}', [AdminProjectController::class, 'destroy']);
        Route::post('/projects/{id}/images', [AdminProjectController::class, 'uploadImage']);
        Route::delete('/projects/images/{imageId}', [AdminProjectController::class, 'deleteImage']);
        Route::post('/projects-reorder', [AdminProjectController::class, 'reorder']);

        // Library Management System screenshots
        Route::get('/library-dashboards', [AdminLibraryDashboardController::class, 'index']);
        Route::post('/library-dashboards/{id}', [AdminLibraryDashboardController::class, 'update']);
        Route::delete('/library-dashboards/{id}/image', [AdminLibraryDashboardController::class, 'removeImage']);

        // Skills management
        Route::get('/skills', [AdminSkillController::class, 'index']);
        Route::post('/skills', [AdminSkillController::class, 'store']);
        Route::put('/skills/{id}', [AdminSkillController::class, 'update']);
        Route::delete('/skills/{id}', [AdminSkillController::class, 'destroy']);
        Route::post('/skills-reorder', [AdminSkillController::class, 'reorder']);

        // Services management
        Route::get('/services', [AdminServiceController::class, 'index']);
        Route::post('/services', [AdminServiceController::class, 'store']);
        Route::put('/services/{id}', [AdminServiceController::class, 'update']);
        Route::delete('/services/{id}', [AdminServiceController::class, 'destroy']);

        // Education management
        Route::get('/education', [AdminEducationController::class, 'index']);
        Route::post('/education', [AdminEducationController::class, 'store']);
        Route::put('/education/{id}', [AdminEducationController::class, 'update']);
        Route::delete('/education/{id}', [AdminEducationController::class, 'destroy']);

        // Contact messages management
        Route::get('/messages', [AdminContactMessageController::class, 'index']);
        Route::get('/messages/{id}', [AdminContactMessageController::class, 'show']);
        Route::patch('/messages/{id}/toggle-read', [AdminContactMessageController::class, 'toggleRead']);
        Route::delete('/messages/{id}', [AdminContactMessageController::class, 'destroy']);

        // Site settings & content editor
        Route::get('/settings', [AdminSettingController::class, 'index']);
        Route::post('/settings', [AdminSettingController::class, 'update']);
        Route::post('/settings/avatar', [AdminSettingController::class, 'uploadAvatar']);
        Route::delete('/settings/avatar', [AdminSettingController::class, 'removeAvatar']);
        Route::post('/settings/cv', [AdminSettingController::class, 'uploadCv']);
        Route::delete('/settings/cv', [AdminSettingController::class, 'removeCv']);

        // Categories & Technologies
        Route::get('/categories', [AdminCategoryController::class, 'index']);
        Route::post('/categories', [AdminCategoryController::class, 'store']);
        Route::put('/categories/{id}', [AdminCategoryController::class, 'update']);
        Route::delete('/categories/{id}', [AdminCategoryController::class, 'destroy']);

        Route::get('/technologies', [AdminTechnologyController::class, 'index']);
        Route::post('/technologies', [AdminTechnologyController::class, 'store']);
        Route::put('/technologies/{id}', [AdminTechnologyController::class, 'update']);
        Route::delete('/technologies/{id}', [AdminTechnologyController::class, 'destroy']);
    });
});
