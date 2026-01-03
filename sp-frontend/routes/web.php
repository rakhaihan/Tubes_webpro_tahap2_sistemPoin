<?php

use Illuminate\Support\Facades\Route;
Route::get('/', fn () => view('auth.login'))->name('login');
Route::get('/dashboard', fn () => view('dashboard'))->name('dashboard');
Route::get('/data-murid', fn () => view('data-murid'))->name('data.murid');
Route::get('/catatan-pelanggaran', fn () => view('catatan-pelanggaran'))->name('pelanggaran');
Route::get('/sanksi-pembinaan', fn () => view('sanksi-pembinaan'))->name('sanksi');
