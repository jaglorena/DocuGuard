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
}
