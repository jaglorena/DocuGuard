<?php

use App\Http\Controllers\GraficoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/graficos', [GraficoController::class, 'index'])->name('Graficos.index');
Route::get('/reportes/data', [GraficoController::class, 'data'])->name('reportes.data');
