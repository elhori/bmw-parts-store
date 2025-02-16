<?php

use App\Presentation\Controllers\AuthController;
use App\Presentation\Controllers\CartController;
use App\Presentation\Controllers\CategoryController;
use App\Presentation\Controllers\OrderController;
use App\Presentation\Controllers\OrderItemController;
use App\Presentation\Controllers\ProductController;
use App\Presentation\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Auth Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
Route::middleware('auth:sanctum')->put('/change-password', [AuthController::class, 'changePassword']);

// Category Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{id}', [CategoryController::class, 'show']);
    Route::get('/categories/search/{searchTerm}', [CategoryController::class, 'search']);
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::put('/categories/{id}', [CategoryController::class, 'update']);
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);
});

// Order Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{id}', [OrderController::class, 'show']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::put('/orders/{id}', [OrderController::class, 'update']);
    Route::delete('/orders/{id}', [OrderController::class, 'destroy']);
});

// OrderItem Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/order-items', [OrderItemController::class, 'index']);
    Route::get('/order-items/{id}', [OrderItemController::class, 'show']);
    Route::post('/order-items', [OrderItemController::class, 'store']);
    Route::put('/order-items/{id}', [OrderItemController::class, 'update']);
    Route::delete('/order-items/{id}', [OrderItemController::class, 'destroy']);
});

// Product Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{id}', [ProductController::class, 'show']);
    Route::get('/products/search/{searchTerm}', [ProductController::class, 'search']);
    Route::post('/products', [ProductController::class, 'store']);
    Route::put('/products/{id}', [ProductController::class, 'update']);
    Route::delete('/products/{id}', [ProductController::class, 'destroy']);
});

// User Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::get('/users/search/{searchTerm}', [UserController::class, 'search']);
    Route::post('/users', [UserController::class, 'store']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);
    Route::put('/users/change-password/{id}', [UserController::class, 'changePassword']);
    Route::put('/users/{id}/make-admin', [UserController::class, 'changeRoleToAdmin']);
    Route::put('/users/{id}/make-manager', [UserController::class, 'changeRoleToManager']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/cart', [CartController::class, 'getCart']);
    Route::post('/cart/add', [CartController::class, 'addProductToCart']);
    Route::delete('/cart/remove', [CartController::class, 'removeProductFromCart']);
    Route::put('/cart/update', [CartController::class, 'updateCartItemQuantity']);
});
