<?php
namespace App\Repositories\Eloquent;

use App\Models\Documento;
use App\Repositories\Interfaces\RepositoryInterface;

class DocumentoRepository implements RepositoryInterface
{
    public function all()
    {
        return Documento::all();
    }

    public function find($id)
    {
        return Documento::findOrFail($id);
    }

    public function create(array $data)
    {
        return Documento::create($data);
    }

    public function update($id, array $data)
    {
        $documento = Documento::findOrFail($id);
        $documento->update($data);
        return $documento;
    }

    public function delete($id)
    {
        return Documento::destroy($id);
    }
}
