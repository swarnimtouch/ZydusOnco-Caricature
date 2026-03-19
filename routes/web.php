<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use \App\Http\Controllers\DoctorController;

Route::get('/', [DoctorController::class, 'index']);
Route::post('/store-doctor', [DoctorController::class, 'store']);
Route::get('/get-employee/{code}', [DoctorController::class, 'getEmployee']);



ROute::get('import-employees',[AdminController::class, 'importEmployeesForm']);
Route::post('/import-employees', [AdminController::class, 'importEmployees']);
