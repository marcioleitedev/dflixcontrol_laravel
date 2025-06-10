<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\PerfilAffiliateController;
use App\Http\Controllers\PlansController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RolerController;
use App\Http\Controllers\SignatureController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;

Route::post('login', [AuthController::class, 'login']);
Route::middleware('auth:api')->post('logout', [AuthController::class, 'logout']);

Route::get('/plans', [PlansController::class, 'index']);
Route::get('/plans/{id}', [PlansController::class, 'show']);
Route::post('/signature', [SignatureController::class, 'store']);
Route::post('/forgot', [SignatureController::class, 'forgot']);
Route::post('/change', [SignatureController::class, 'change']);

Route::middleware('auth:api')->group(function () {

    Route::get('/signature', [SignatureController::class, 'index']);
    Route::get('/signature/{id}', [SignatureController::class, 'show']);
    Route::get('/signature/cliente/{id}', [SignatureController::class, 'showUser']);

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
    Route::put('/roles/{id}', [RolerController::class, 'update']);
    Route::delete('/roles/{id}', [RolerController::class, 'destroy']);

    // Perfil Affiliate
    Route::delete('/profile/{id}', [PerfilAffiliateController::class, 'destroy']);
    Route::put('/profile/{id}', [PerfilAffiliateController::class, 'update']);
    Route::get('/profile/{id}', [PerfilAffiliateController::class, 'show']);
    Route::post('/profile', [PerfilAffiliateController::class, 'store']);
    Route::get('/profile', [PerfilAffiliateController::class, 'index']);

    // Company
    Route::get('/company', [CompanyController::class, 'index']);
    Route::put('/company/{id}', [CompanyController::class, 'update']);
    Route::get('/company/{id}', [CompanyController::class, 'show']);
    Route::delete('/company/{id}', [CompanyController::class, 'destroy']);
    Route::post('/company', [CompanyController::class, 'store']);

    // Category
    Route::get('/category', [CategoryController::class, 'index']);
    Route::get('/category/{id}', [CategoryController::class, 'show']);
    Route::post('/category', [CategoryController::class, 'store']);
    Route::put('/category/{id}', [CategoryController::class, 'update']);
    Route::delete('/category/{id}', [CategoryController::class, 'destroy']);

    //Products
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{id}', [ProductController::class, 'show']);
    Route::post('/products', [ProductController::class, 'store']);
    Route::put('/products/{id}', [ProductController::class, 'update']);
    Route::delete('/products/{id}', [ProductController::class, 'destroy']);

    // Suppliers Fornecedores
    Route::get('/fornecedores', [SupplierController::class, 'index']);
    Route::get('/fornecedores/{id}', [SupplierController::class, 'show']);
    Route::post('/fornecedores', [SupplierController::class, 'store']);
    Route::put('/fornecedores/{id}', [SupplierController::class, 'update']);
    Route::delete('/fornecedores/{id}', [SupplierController::class, 'destroy']);

    // Empregados
    Route::get('/empregados', [EmployeeController::class, 'index']);
    Route::get('/empregados/{id}', [EmployeeController::class, 'show']);
    Route::post('/empregados', [EmployeeController::class, 'store']);
    Route::put('/empregados/{id}', [EmployeeController::class, 'update']);
    Route::delete('/empregados/{id}', [EmployeeController::class, 'destroy']);
});
