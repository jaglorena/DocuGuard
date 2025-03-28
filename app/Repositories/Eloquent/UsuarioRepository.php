<?php
namespace App\Repositories\Eloquent;

use App\Models\Usuario;
use App\Repositories\Interfaces\RepositoryInterface;

class UsuarioRepository implements RepositoryInterface
{
    public function all()
    {
        return Usuario::all();
    }

    public function find($id)
    {
        return Usuario::findOrFail($id);
    }

    public function create(array $data)
    {
        return Usuario::create($data);
    }

    public function update($id, array $data)
    {
        $usuario = Usuario::findOrFail($id);
        $usuario->update($data);
        return $usuario;
    }

    public function delete($id)
    {
        return Usuario::destroy($id);
    }
}
