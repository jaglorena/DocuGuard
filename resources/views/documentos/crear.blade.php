@extends('documentos.layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">📤 Subir Nuevo Documento</h2>

    <form action="{{ route('documentos.store') }}" method="POST" enctype="multipart/form-data" class="card p-4 shadow-sm">
        @csrf

        <div class="form-group mb-3">
            <label for="titulo">Título</label>
            <input type="text" name="titulo" class="form-control" required>
        </div>

        <div class="form-group mb-3">
            <label for="descripcion">Descripción</label>
            <textarea name="descripcion" class="form-control" rows="3"></textarea>
        </div>

        <div class="form-group mb-3">
            <label for="archivo">Archivo</label>
            <input type="file" name="archivo" class="form-control" required>
        </div>

        <div class="form-group mb-3">
            <label for="estado">Estado</label>
            <select name="estado" class="form-select">
                <option value="borrador">Borrador</option>
                <option value="publicado">Publicado</option>
            </select>
        </div>

        <div class="form-group mb-4">
            <label for="version">Versión</label>
            <input type="text" name="version" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Subir Documento</button>
    </form>
</div>
@endsection
