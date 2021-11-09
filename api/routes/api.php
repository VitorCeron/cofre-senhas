<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\UserPasswordsController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ActivityLogController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);    
});

//e-mail de verificação de cadastro de usuário
Route::get('email/verify/{id}', [VerificationController::class, 'verify'])->name('verification.verify');
Route::get('email/resend', [VerificationController::class, 'resend'])->name('verification.resend');

//recuperação de senha
Route::group([
    'middleware' => 'api',
    'prefix' => 'password'

], function ($router) {
    Route::post('email', [ForgotPasswordController::class, 'forgot']);
    Route::post('reset', [ForgotPasswordController::class, 'reset'])->name('password.reset');
});

//senhas do usuário
Route::group([
    'middleware' => 'auth:api',
    'prefix' => 'user_passwords'

], function ($router) {
    Route::get('/get', [UserPasswordsController::class, 'get']);
    Route::get('/show/{id}', [UserPasswordsController::class, 'getById']);
    Route::post('/store', [UserPasswordsController::class, 'store']);
    Route::put('/update/{id}', [UserPasswordsController::class, 'update']);
    Route::delete('/destroy/{id}', [UserPasswordsController::class, 'destroy']);
});

//logs
Route::group([
    'middleware' => 'auth:api',
    'prefix' => 'logs'

], function ($router) {
    Route::get('/get', [ActivityLogController::class, 'get']);
});