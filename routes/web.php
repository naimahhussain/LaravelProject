<?php
use App\Http\Controllers\Admin\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontController;

Route::get('/', [FrontController::class, 'index']);
Route::get('/admin', [AdminController::class, 'index']);

Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->name('admin.dashboard');