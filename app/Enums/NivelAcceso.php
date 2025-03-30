<?php

namespace App\Enums;

enum NivelAcceso: string {
    case LECTURA = 'lectura';
    case ESCRITURA = 'escritura';
    case ELIMINACION = 'eliminacion';
}
