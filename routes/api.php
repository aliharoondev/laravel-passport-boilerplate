<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Auth\RegisteredUserController;
use App\Http\Controllers\API\Auth\VerifyEmailController;
use App\Http\Controllers\API\Auth\UserLoginController;
use App\Http\Controllers\Api\Auth\PasswordResetLinkController;
use App\Http\Controllers\Api\Auth\NewPasswordController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\Role\AssignPermissionsToRoleController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\User\AssignRoleToUserController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


# ---------------------- User Auth Routes ----------------- #

Route::post('register', [RegisteredUserController::class, 'store'])->middleware('guest')->name('register');
Route::get('email/verify/{id}/{hash}', [VerifyEmailController::class, 'verifyAccount'])->name('verification.verify');
Route::post('login', [UserLoginController::class, 'login'])->name('login');

Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.forgot');
Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.reset');

Route::post('logout', [UserLoginController::class, 'logout'])->middleware('auth:api')->name('logout');

Route::middleware(['auth:api'])->group(function () {

    # -------------- Role Routes -------------------- #

    Route::apiResource('roles', RoleController::class);
    Route::prefix('role')->group(function () {
        Route::post('assign-permissions', [AssignPermissionsToRoleController::class, 'store']);
    });

    # -------------- Permission Routes -------------------- #

    Route::apiResource('permissions', PermissionController::class);

    # -------------- User Routes -------------------- #

    Route::prefix('user')->group(function () {
        Route::post('assign-role', [AssignRoleToUserController::class, 'store']);
    });
});
