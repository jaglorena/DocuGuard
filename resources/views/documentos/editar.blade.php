@extends('documentos.layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">✏️ Editar Documento</h2>

    <form action="{{ route('documentos.update', $documento->id) }}" method="POST" class="card p-4 shadow-sm">
        @csrf
        @method('PUT')

        <div class="form-group mb-3">
            <label for="titulo">Título</label>
            <input type="text" name="titulo" class="form-control" value="{{ $documento->titulo }}" required>
        </div>

        <div class="form-group mb-3">
            <label for="descripcion">Descripción</label>
            <textarea name="descripcion" class="form-control" rows="3">{{ $documento->descripcion }}</textarea>
        </div>

        <div class="form-group mb-3">
            <label for="estado">Estado</label>
            <select name="estado" class="form-select">
                <option value="borrador" @if($documento->estado === 'borrador') selected @endif>Borrador</option>
                <option value="publicado" @if($documento->estado === 'publicado') selected @endif>Publicado</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Actualizar Documento</button>
    </form>
</div>
@endsection
