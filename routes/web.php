<?php

use Illuminate\Support\Facades\Route;

// Redirect root to login
Route::redirect('/', '/login');

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\PelanggaranController;
use App\Http\Controllers\PembinaanController;
use App\Http\Controllers\DashboardController;

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/admin', [DashboardController::class,'index'])->name('dashboard.admin');
    Route::get('/guru', [DashboardController::class,'index'])->name('dashboard.guru');
    Route::get('/siswa', [DashboardController::class,'index'])->name('dashboard.siswa');

    // Students
    Route::get('/students', [StudentController::class,'index'])->name('students.index');
    Route::get('/students/create', [StudentController::class,'create'])->name('students.create');
    Route::post('/students', [StudentController::class,'store'])->name('students.store');
    Route::get('/students/{student}/edit', [StudentController::class,'edit'])->name('students.edit');
    Route::put('/students/{student}', [StudentController::class,'update'])->name('students.update');
    Route::delete('/students/{student}', [StudentController::class,'destroy'])->name('students.destroy');

    // Pelanggaran
    Route::get('/pelanggaran/create', [PelanggaranController::class,'create'])->name('pelanggaran.create');
    Route::post('/pelanggaran', [PelanggaranController::class,'store'])->name('pelanggaran.store');
    Route::get('/pelanggaran', [PelanggaranController::class,'index'])->name('pelanggaran.index');
    Route::get('/pelanggaran/print', [PelanggaranController::class,'print'])->name('pelanggaran.print');

    // Pembinaan / Sanksi
    Route::get('/pembinaan', [PembinaanController::class,'index'])->name('pembinaan.index');
    Route::get('/pembinaan/create', [PembinaanController::class,'create'])->name('pembinaan.create');
    Route::post('/pembinaan', [PembinaanController::class,'store'])->name('pembinaan.store');
    Route::get('/pembinaan/{pembinaan}/edit', [PembinaanController::class,'edit'])->name('pembinaan.edit');
    Route::put('/pembinaan/{pembinaan}', [PembinaanController::class,'update'])->name('pembinaan.update');
    Route::delete('/pembinaan/{pembinaan}', [PembinaanController::class,'destroy'])->name('pembinaan.destroy');
});
