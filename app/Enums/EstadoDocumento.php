<?php
namespace App\Enums;

enum EstadoDocumento: string {
    case Activo      = "activo";
    case Archivado   = "archivado";
    case EnRevision = "en revision";
}
