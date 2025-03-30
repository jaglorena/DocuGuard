<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/dashboard', function () {
    $rol = Auth::user()->rol;

    if ($rol === 'Administrador') {
        return view('Usuario.dashboard_admin');
    }

    return view('Usuario.dashboard_usuario');
})->middleware(['auth'])->name('dashboard');

