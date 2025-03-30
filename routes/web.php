<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocumentoController;

/*Route::middleware(['auth'])->group(function () {
    Route::resource('documento', DocumentoController::class);
}); */

Route::resource('documentos', DocumentoController::class);
