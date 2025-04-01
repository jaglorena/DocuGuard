<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DocumentoController;
use App\Http\Controllers\GraficoController;
use App\Http\Controllers\PermisoController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\UsuarioController;
use App\Http\Middleware\DocumentosMiddleware;
use App\Http\Middleware\RolMiddleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth', RolMiddleware::class])->group(function () {

    Route::resource('usuarios', UsuarioController::class);
    Route::get('/usuarios/{usuario}/cambiar-password', [UsuarioController::class, 'formCambiarPassword'])->name('usuarios.cambiar-password.form');
    Route::put('/usuarios/{usuario}/cambiar-password', [UsuarioController::class, 'cambiarPassword'])->name('usuarios.cambiar-password');

    Route::get('/permisos', [PermisoController::class, 'index'])->name('permisos.index');

    Route::get('/permisos/create', [PermisoController::class, 'create'])->name('permisos.create');
    Route::post('/permisos', [PermisoController::class, 'store'])->name('permisos.store');

    Route::get('/permisos/{id}/edit', [PermisoController::class, 'edit'])->name('permisos.edit');
    Route::put('/permisos/{id}', [PermisoController::class, 'update'])->name('permisos.update');

    Route::delete('/permisos/{id}', [PermisoController::class, 'destroy'])->name('permisos.destroy');
});

Route::middleware([DocumentosMiddleware::class, 'auth'])->group(function () {
    Route::put('/documentos/{id}', [DocumentoController::class, 'update'])->name('documentos.update');
    Route::get('/documentos/{id}', [DocumentoController::class, 'show'])->name('documentos.show');
});

Route::get('/dashboard', function () {
    $rol = Auth::user()->rol;

    if ($rol === 'Administrador') {
        return view('Usuario.dashboard_admin');
    }

    return view('Usuario.dashboard_usuario');
})->middleware(['auth'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('/documentos', [DocumentoController::class, 'index'])->name('documentos.index');
    Route::get('/reportes', [ReporteController::class, 'index'])->name('reportes.index');

    Route::get('/reportes/documentos-csv', [ReporteController::class, 'descargarDocumentosCSV'])->name('reportes.documentos.csv');
    Route::get('/reportes/permisos-csv', [ReporteController::class, 'descargarPermisosCSV'])->name('reportes.permisos.csv');
    Route::get('/reportes/actividad-csv', [ReporteController::class, 'descargarActividadCSV'])->name('reportes.actividad.csv');
    Route::get('/reportes/estado-csv', [ReporteController::class, 'descargarDocumentosEstadoCSV'])->name('reportes.estado.csv');

    Route::get('/reportes/documentos', [ReporteController::class, 'verDocumentosPDF'])->name('reportes.documentos.vista');
    Route::get('/reportes/permisos', [ReporteController::class, 'verPermisosPDF'])->name('reportes.permisos.vista');
    Route::get('/reportes/actividad', [ReporteController::class, 'verActividadPDF'])->name('reportes.actividad.vista');
    Route::get('/reportes/estado', [ReporteController::class, 'verEstadoPDF'])->name('reportes.estado.vista');
    Route::get('/graficos', [GraficoController::class, 'index'])->name('Graficos.index');
    Route::get('/reportes/data', [GraficoController::class, 'data'])->name('reportes.data');
});
