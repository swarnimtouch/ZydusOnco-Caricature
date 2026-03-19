<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DoctorController;

Route::get('/',                    [DoctorController::class, 'index']);
Route::post('/store-doctor',       [DoctorController::class, 'store'])->name('store.doctor');
Route::get('/get-employee/{code}', [DoctorController::class, 'getEmployee'])->name('get.employee');

Route::get('/import-employees',    [AdminController::class, 'importEmployeesForm'])->name('import.employees.form');
Route::post('/import-employees',   [AdminController::class, 'importEmployees'])->name('import.employees');

Route::prefix('admin')->group(function () {
    Route::get('/login',  [AdminController::class, 'showLoginForm'])->name('login');       // 'login' naam zaroori hai — auth middleware yahi dhundta hai
    Route::post('/login', [AdminController::class, 'login'])->name('admin.login.submit');
    Route::post('/logout',[AdminController::class, 'logout'])->name('admin.logout');
});

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard',       [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/doctors',         [AdminController::class, 'index'])->name('doctors.index');
    Route::delete('/doctors/{id}', [AdminController::class, 'destroy'])->name('doctors.destroy');  // duplicate /admin/admin/ fix
    Route::get('/doctors/export',  [AdminController::class, 'export'])->name('doctors.export');    // duplicate /admin/admin/ fix
});
