<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    protected $table = 'documentos';
    protected $primaryKey = 'id_documento';

    protected $fillable = [
        'titulo',
        'descripcion',
        'ruta_archivo',
        'fecha_subida',
        'version',
        'estado',
        'id_usuario_subida',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario_subida');
    }

    public function permisos()
    {
        return $this->hasMany(Permiso::class, 'id_documento');
    }
}
