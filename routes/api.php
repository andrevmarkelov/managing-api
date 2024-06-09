<?php

use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Middleware\ForceJsonResponse;
use App\Http\Middleware\VerifyApiToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group(['prefix' => 'v1', 'middleware' => [ForceJsonResponse::class, VerifyApiToken::class]], function () {
    // Department routes
    Route::get('departments/{limit?}', [DepartmentController::class, 'index'])->name('department.index.api');
    Route::post('department', [DepartmentController::class, 'store'])->name('department.store.api');
    Route::put('department/{id}', [DepartmentController::class, 'update'])->name('department.update.api');
    Route::delete('department/{id}', [DepartmentController::class, 'destroy'])->name('department.destroy.api');

    // Employee routes
    Route::get('employees/{limit?}', [EmployeeController::class, 'index'])->name('employee.index.api');
    Route::post('employee', [EmployeeController::class, 'store'])->name('employee.store.api');
    Route::put('employee/{id}', [EmployeeController::class, 'update'])->name('employee.update.api');
    Route::delete('employee/{id}', [EmployeeController::class, 'destroy'])->name('employee.destroy.api');
});
