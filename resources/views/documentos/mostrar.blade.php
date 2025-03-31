@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card p-4 shadow-sm">
        <h2>{{ $documento->titulo }}</h2>

        <p><strong>Descripción:</strong> {{ $documento->descripcion }}</p>
        <p><strong>Estado:</strong>
            <span class="badge bg-{{ $documento->estado === 'publicado' ? 'success' : 'secondary' }}">
                {{ ucfirst($documento->estado) }}
            </span>
        </p>
        <p><strong>Versión:</strong> {{ $documento->version }}</p>

        <a href="{{ asset('storage/' . $documento->archivo) }}" class="btn btn-primary mt-3" target="_blank">
            Ver o Descargar Archivo
        </a>

        <hr class="my-4">

        <h5>📜 Historial de Versiones</h5>
        <ul class="list-group">
            @foreach($versiones as $ver)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    Versión {{ $ver->version }}
                    <small>{{ $ver->created_at->format('d/m/Y H:i') }}</small>
                </li>
            @endforeach
        </ul>

        @if(auth()->user()->rol === 'admin')
            <hr class="my-4">

            <h5>👥 Usuarios con Acceso</h5>
            <table class="table table-bordered mt-3">
                <thead class="bg-light">
                    <tr>
                        <th>Usuario</th>
                        <th>Correo</th>
                        <th>Permiso</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($permisos as $permiso)
                        <tr>
                            <td>{{ $permiso->usuario->nombre }} {{ $permiso->usuario->apellido }}</td>
                            <td>{{ $permiso->usuario->correo }}</td>
                            <td>
                                @php
                                    $color = match($permiso->nivel_acceso) {
                                        'lectura' => 'bg-blue-100 text-blue-700',
                                        'escritura' => 'bg-yellow-100 text-yellow-700',
                                        'eliminacion' => 'bg-red-100 text-red-700',
                                        default => 'bg-gray-100 text-gray-700'
                                    };
                                @endphp
                                <span class="px-2 py-1 rounded text-sm font-semibold {{ $color }}">
                                    {{ ucfirst($permiso->nivel_acceso) }}
                                </span>
                            </td>
                            <td class="flex gap-2">
                                <a href="{{ route('permisos.edit', $permiso->id_permiso) }}" class="text-blue-600 hover:underline">Editar</a>

                                <form action="{{ route('permisos.destroy', $permiso->id_permiso) }}" method="POST" onsubmit="return confirm('¿Estás seguro de quitar este permiso?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
@endsection
