<?php
namespace App\Repositories\Eloquent;

use App\Models\Permiso;
use App\Repositories\Interfaces\RepositoryInterface;

class PermisoRepository implements RepositoryInterface
{
    public function all()
    {
        return Permiso::all();
    }

    public function find($id)
    {
        return Permiso::findOrFail($id);
    }

    public function create(array $data)
    {
        return Permiso::create($data);
    }

    public function update($id, array $data)
    {
        $permiso = Permiso::findOrFail($id);
        $permiso->update($data);
        return $permiso;
    }

    public function delete($id)
    {
        return Permiso::destroy($id);
    }
}
