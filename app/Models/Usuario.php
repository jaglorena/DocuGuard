<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use Notifiable;

    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuario';

    protected $fillable = [
        'nombre',
        'apellido',
        'correo',
        'clave',
        'rol',
    ];

    protected $hidden = [
        'clave',
    ];

    public function getAuthPassword()
    {
        return $this->clave;
    }

    public function documentosSubidos()
    {
        return $this->hasMany(Documento::class, 'id_usuario_subida');
    }

    public function permisos()
    {
        return $this->hasMany(Permiso::class, 'id_usuario');
    }

    public function tienePermiso($documentoId, $nivel)
    {
        return $this->permisos()
            ->where('id_documento', $documentoId)
            ->where('nivel_acceso', $nivel)
            ->exists();
    }

    public function colorPermiso($documentoId)
    {
        $permisos = $this->permisos()
            ->where('id_documento', $documentoId)
            ->pluck('nivel_acceso')
            ->toArray();

        if (in_array('lectura', $permisos) && in_array('escritura', $permisos) && in_array('eliminacion', $permisos)) {
            return 'bg-green-100 border-green-500'; // Todos los permisos
        } elseif (in_array('eliminacion', $permisos)) {
            return 'bg-red-100 border-red-500';
        } elseif (in_array('escritura', $permisos)) {
            return 'bg-yellow-100 border-yellow-500';
        } elseif (in_array('lectura', $permisos)) {
            return 'bg-blue-100 border-blue-500';
        }

        return 'bg-gray-100 border-gray-300'; // Sin permiso específico
    }

}
