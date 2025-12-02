<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\Auth\AuthController;

// Public
Route::post('/login', [AuthController::class, 'login']);

// Auth
Route::middleware(['auth:sanctum', 'tenant'])->group(function () {
  Route::post('/register', [AuthController::class, 'register']);
  Route::post('/logout', [AuthController::class, 'logout']);
  Route::get('/me', [AuthController::class, 'me']);
});

// Dashboard
Route::middleware(['auth:sanctum', 'tenant'])->group(function () {
  Route::get('/stats', [DashboardController::class, 'stats']);
});

// Dashboard
Route::middleware(['auth:sanctum', 'tenant'])->group(function () {
  Route::get('/users', [UserController::class, 'index']);
});

// Vendors
Route::middleware(['auth:sanctum', 'tenant'])->group(function () {

  Route::get('/vendors', [VendorController::class, 'index']);
  Route::post('/vendors', [VendorController::class, 'store']);
  Route::get('/vendors/{id}', [VendorController::class, 'show']);
  Route::put('/vendors/{id}', [VendorController::class, 'update']);
  Route::delete('/vendors/{id}', [VendorController::class, 'destroy']);
});

// Invoice
Route::middleware(['auth:sanctum', 'tenant'])->group(function () {

  Route::get('/invoices', [InvoiceController::class, 'index']);
  Route::post('/invoices', [InvoiceController::class, 'store']);
  Route::get('/invoices/{id}', [InvoiceController::class, 'show']);
  Route::put('/invoices/{id}', [InvoiceController::class, 'update']);
  Route::delete('/invoices/{id}', [InvoiceController::class, 'destroy']);
});

// Vendors
Route::middleware(['auth:sanctum', 'tenant'])->group(function () {

  Route::get('/organizations', [OrganizationController::class, 'index']);
});
