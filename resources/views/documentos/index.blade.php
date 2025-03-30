@extends('documentos.layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">📚 Lista de Documentos</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card p-3 shadow-sm">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Título</th>
                    <th>Versión</th>
                    <th>Estado</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($documentos as $documento)
                    <tr>
                        <td>{{ $documento->titulo }}</td>
                        <td>{{ $documento->version }}</td>
                        <td>
                            <span class="badge bg-{{ $documento->estado === 'publicado' ? 'success' : 'secondary' }}">
                                {{ ucfirst($documento->estado) }}
                            </span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('documentos.show', $documento->id_documento) }}" class="btn btn-sm btn-primary">Ver</a>

                            @php
                                $permiso = $documento->permisos->where('id_usuario', auth()->user()->id_usuario)->first();
                            @endphp

                            @if($permiso && in_array($permiso->nivel_acceso, ['escritura', 'eliminación']))
                                <a href="{{ route('documentos.edit', $documento->id_documento) }}" class="btn btn-sm btn-warning">Editar</a>
                            @endif

                            @if($permiso && $permiso->nivel_acceso === 'eliminación')
                                <form action="{{ route('documentos.destroy', $documento->id_documento) }}" method="POST" class="d-inline-block" onsubmit="return confirm('¿Eliminar este documento?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Eliminar</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
