<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DocumentoController;
use App\Http\Controllers\GraficoController;
use App\Http\Controllers\PermisoController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\UsuarioAdminController;
use App\Http\Controllers\ReporteController;



Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
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


Route::put('/documentos/{id}', [DocumentoController::class, 'update'])->name('documentos.update');
Route::get('/graficos', [DocumentoController::class, 'vistaGraficos'])->name('graficos.index');
Route::get('/graficos/data', [DocumentoController::class, 'datosGraficos'])->name('reportes.data');


Route::middleware(['auth'])->prefix('reportes')->group(function () {
    Route::get('/', [ReporteController::class, 'index'])->name('reportes.index');

    Route::get('/documentos/csv', [ReporteController::class, 'descargarDocumentosCSV'])->name('reportes.documentos.csv');
    Route::get('/permisos/csv', [ReporteController::class, 'descargarPermisosCSV'])->name('reportes.permisos.csv');
    Route::get('/actividad/csv', [ReporteController::class, 'descargarActividadCSV'])->name('reportes.actividad.csv');
    Route::get('/estado/csv', [ReporteController::class, 'descargarDocumentosEstadoCSV'])->name('reportes.estado.csv');
});
Route::get('/reportes/documentos/vista', [ReporteController::class, 'verDocumentosPDF'])->name('reportes.documentos.vista');
Route::get('/reportes/permisos/vista', [ReporteController::class, 'verPermisosPDF'])->name('reportes.permisos.vista');
Route::get('/reportes/actividad/vista', [ReporteController::class, 'verActividadPDF'])->name('reportes.actividad.vista');
Route::get('/reportes/estado/vista', [ReporteController::class, 'verEstadoPDF'])->name('reportes.estado.vista');
Route::get('/reportes/actividad/vista', [ReporteController::class, 'verActividadPDF'])->name('reportes.actividad.vista');
Route::get('/reportes/estado/vista', [ReporteController::class, 'verEstadoPDF'])->name('reportes.estado.vista');
Route::get('/reportes/actividad/vista', [ReporteController::class, 'verActividadPDF'])->name('reportes.actividad.vista');
Route::prefix('reportes')->middleware(['auth'])->group(function () {
Route::get('/documentos/vista', [ReporteController::class, 'verDocumentosPDF'])->name('reportes.documentos.vista');
Route::get('/permisos/vista', [ReporteController::class, 'verPermisosPDF'])->name('reportes.permisos.vista');
Route::get('/actividad/vista', [ReporteController::class, 'verActividadPDF'])->name('reportes.actividad.vista');
Route::get('/estado/vista', [ReporteController::class, 'verEstadoPDF'])->name('reportes.estado.vista');});






