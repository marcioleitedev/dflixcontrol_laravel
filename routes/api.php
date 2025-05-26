<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PlansController;
use App\Http\Controllers\RolerController;
use App\Http\Controllers\SignatureController;
use App\Http\Controllers\UserController;

Route::post('login', [AuthController::class, 'login']);
Route::middleware('auth:api')->post('logout', [AuthController::class, 'logout']);

Route::get('/plans', [PlansController::class, 'index']);
Route::get('/plans/{id}', [PlansController::class, 'show']);
Route::post('/signature', [SignatureController::class, 'store']);
Route::post('/forgot', [SignatureController::class, 'forgot']);
Route::post('/change', [SignatureController::class, 'change']);

Route::get('/signature', [SignatureController::class, 'index']);
Route::get('/signature/{id}', [SignatureController::class, 'show']);

//affiliate
Route::get('/users', [UserController::class, 'index']);
Route::get('/users/{id}', [UserController::class, 'show']);
Route::get('/affiliate', [UserController::class, 'affiliate']);
Route::post('/affiliate', [UserController::class, 'affiliateStore']);
Route::put('/affiliate/{id}', [UserController::class, 'affiliateUpdate']);
Route::delete('/affiliate/{id}', [UserController::class, 'affiliateDelete']);

// roles
Route::get('/roles', [RolerController::class, 'index']);
Route::post('/roles', [RolerController::class, 'store']);

Route::middleware('auth:api')->group(function () {
    Route::get('dashboard', function () {
        return response()->json(['message' => 'Bem vindo ao dashboard CRM!']);
    });

    // Outras rotas do CRM aqui
    Route::get('crm/services', function () {
        return response()->json(['services' => ['Serviço 1', 'Serviço 2']]);
    });
});
