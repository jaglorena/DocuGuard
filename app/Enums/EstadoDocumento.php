<?php
namespace App\Enums;

enum EstadoDocumento: string {
    case ACTIVO      = "Activo";
    case ARCHIVADO   = "Archivado";
    case EN_REVISION = "Revision";
}
