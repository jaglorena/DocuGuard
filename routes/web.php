<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DocumentoController;
use App\Http\Controllers\GraficoController;
use App\Http\Controllers\PermisoController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rutas protegidas solo para ADMINISTRADORES
Route::middleware(['auth'])->group(function () {

    //Gestion de usuarios
    Route::resource('usuarios', UsuarioController::class);
    Route::get('/usuarios/{usuario}/cambiar-password', [UsuarioController::class, 'formCambiarPassword'])->name('usuarios.cambiar-password.form');
    Route::put('/usuarios/{usuario}/cambiar-password', [UsuarioController::class, 'cambiarPassword'])->name('usuarios.cambiar-password');

    // Vista de listado de permisos
    Route::get('/permisos', [PermisoController::class, 'index'])->name('permisos.index');

    // Formulario de creación
    Route::get('/permisos/create', [PermisoController::class, 'create'])->name('permisos.create');
    Route::post('/permisos', [PermisoController::class, 'store'])->name('permisos.store');

    // Formulario de edición
    Route::get('/permisos/{id}/edit', [PermisoController::class, 'edit'])->name('permisos.edit');
    Route::put('/permisos/{id}', [PermisoController::class, 'update'])->name('permisos.update');

    // Eliminación
    Route::delete('/permisos/{id}', [PermisoController::class, 'destroy'])->name('permisos.destroy');
});

Route::get('/dashboard', function () {
    $rol = Auth::user()->rol;

    if ($rol === 'Administrador') {
        return view('Usuario.dashboard_admin');
    }

    return view('Usuario.dashboard_usuario');
})->middleware(['auth'])->name('dashboard');

Route::middleware(['auth'])->group(function () {

    // Permisos
    Route::resource('permisos', PermisoController::class)->except(['show']);

    // Documentos
    Route::resource('documentos', DocumentoController::class);
});

Route::get('/graficos', [GraficoController::class, 'index'])->name('Graficos.index');

Route::get('/reportes/data', [GraficoController::class, 'data'])->name('reportes.data');

Route::get("/", [AuthController::class, 'showLogin'])->name('login');
