<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\CommonController;
use App\Http\Controllers\PackagesController;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\keyGenerateController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\ForgotPasswordController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', [UsersController::class, 'authUser']);


Route::middleware('guest')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', RegisterController::class);
    Route::post('/forgot-password', ForgotPasswordController::class);
    Route::post('/reset-password', ResetPasswordController::class);
    Route::get('/user-exists', [UsersController::class,'isUserExists']);
    // Route::get('/user', [UsersController::class, 'user']);
    });
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

Route::middleware('auth:sanctum')->group(function () {
            //users
            Route::get('/users', [UsersController::class, 'users']);
            Route::get('/user/auth', [UsersController::class, 'authUser']);
            Route::post('/user/password-update', [UsersController::class, 'userPasswordUpdate']);
            Route::post('/users/toggle-verify', [UsersController::class, 'toggleVerify']);
            Route::delete('/users/delete/user/{user}', [UsersController::class, 'deleteUser']);
            Route::post('/users/create-user', [UsersController::class, 'createUser']);
            Route::patch('/users/update-user/{user}', [UsersController::class, 'updateUser']);

            //projects
            Route::get('/projects', [ProjectsController::class, 'getProjects']);
            Route::get('/project/{project}', [ProjectsController::class, 'getProjectById']);
            Route::get('/projects/list', [ProjectsController::class, 'getProjectList']);
            Route::post('/projects/create-project', [ProjectsController::class, 'createProject']);
            Route::post('/projects/update-project/{project}', [ProjectsController::class, 'updateProject']);
            Route::delete('/projects/delete/project/{project}', [ProjectsController::class, 'deleteProject']);

            //packages
            Route::get('/packages', [PackagesController::class, 'getPackages']);
            Route::get('/package/{package}', [PackagesController::class, 'getPackage']);
            Route::post('/package/create-package', [PackagesController::class, 'createPackage']);
            Route::patch('/package/update-package/{package}', [PackagesController::class, 'updatePackage']);
            Route::delete('/package/delete-package/{package}', [PackagesController::class, 'deletePackage']);

            //role
            Route::get('/roles', [RoleController::class, 'index']);
            Route::post('/roles', [RoleController::class, 'store']);
            Route::get('/roles/{id}', [RoleController::class, 'show']);
            Route::put('/roles/{id}', [RoleController::class, 'update']);
            Route::delete('/roles/{id}', [RoleController::class, 'destroy']);


            //common

            Route::get('/get_project_types', [CommonController::class, 'getProjectTypes']);
            Route::get('/get_environment_types', [CommonController::class, 'getEnvironmentTypes']);
            Route::post('/upload', [FileUploadController::class, 'upload']);


});

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
