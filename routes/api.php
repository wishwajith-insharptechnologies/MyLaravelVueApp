<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\PackagesController;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\keyGenerateController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\ForgotPasswordController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('guest')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', RegisterController::class);
    Route::post('/forgot-password', ForgotPasswordController::class);
    Route::post('/reset-password', ResetPasswordController::class);
});

// Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [UsersController::class, 'user']);
    Route::get('/users', [UsersController::class, 'users']);
    Route::post('/users/toggle-verify', [UsersController::class, 'toggleVerify']);
    Route::delete('/users/delete/user/{user}', [UsersController::class, 'deleteUser']);
    Route::post('/users/create-user', [UsersController::class, 'createUser']);
    Route::patch('/users/update-user/{user}', [UsersController::class, 'updateUser']);

            //projects
            Route::get('/projects', [ProjectsController::class, 'getProjects']);
            Route::get('/project/{project}', [ProjectsController::class, 'getProjectById']);
            Route::get('/projects/list', [ProjectsController::class, 'getProjectList']);
            Route::post('/projects/create-project', [ProjectsController::class, 'createProject']);
            Route::patch('/projects/update-project/{project}', [ProjectsController::class, 'updateProject']);
            Route::delete('/projects/delete/project/{project}', [ProjectsController::class, 'deleteProject']);

            //packages
            Route::get('/packages', [PackagesController::class, 'getPackages']);
            Route::post('/package/create-package', [PackagesController::class, 'createPackage']);
            Route::patch('/projects/update-package/{package}', [PackagesController::class, 'updatePackage']);
            Route::delete('/package/delete-package/{package}', [PackagesController::class, 'deletePackage']);


// });

Route::post('/payment/get-session',  [PaymentsController::class, 'getSession']);
Route::post('/payment/complete',  [PaymentsController::class, 'paymentComplete']);
Route::get('/package/{package}', [PackagesController::class, 'getPackage']);

Route::post('/paymnet/stripe/webhook',  [PaymentsController::class, 'listeningWebhook']);
Route::get('/test',  [PaymentsController::class, 'test']);


Route::post('/key/get-permission-key',  [keyGenerateController::class, 'getPermissionKey']);

Route::post('/key/active-verification',  [keyGenerateController::class, 'keyActiveVerification']);

Route::get('/test/get-product-key',  [keyGenerateController::class, 'getProductKey']);

// Route::get('/key/get-permission-key',  [keyGenerateController::class, 'getPermissionKey']);


Route::get('/test/public/url',  [PaymentsController::class, 'testPublicUrl']);
Route::get('/test/decrypt/key',  [PaymentsController::class, 'testDecryptKey']);
