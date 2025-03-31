@extends('Usuario.layouts.app')

@section('content')
<div class="max-w-4xl mx-auto mt-8 bg-white p-6 rounded shadow">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-[#155f82]">{{ $documento->titulo }}</h2>
        @if(auth()->user()->rol === 'admin')
            <a href="{{ route('documentos.create', ['titulo' => $documento->titulo]) }}"
               class="inline-flex items-center gap-2 bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
                ➕ Nueva Versión
            </a>
        @endif
    </div>

    <div class="mb-4">
        <p class="text-gray-700"><strong>Descripción:</strong> {{ $documento->descripcion }}</p>
        <p class="text-gray-700"><strong>Estado:</strong>
            <span class="inline-block px-2 py-1 rounded text-sm font-semibold 
                {{ $documento->estado === 'activo' ? 'bg-green-100 text-green-700' : ($documento->estado === 'borrador' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-200 text-gray-700') }}">
                {{ ucfirst($documento->estado) }}
            </span>
        </p>
        <p class="text-gray-700"><strong>Versión:</strong> {{ str_pad($documento->version, 4, '0', STR_PAD_LEFT) }}</p>
    </div>

    <a href="{{ asset('storage/' . $documento->ruta_archivo) }}"
       target="_blank"
       class="inline-flex items-center gap-2 bg-[#155f82] text-white px-4 py-2 rounded hover:bg-[#0d4866] transition mb-4">
        📥 Ver o Descargar Archivo
    </a>

    <hr class="my-6">

    <h4 class="text-xl font-semibold text-[#155f82] mb-3">Historial de Versiones</h4>
    <ul class="divide-y divide-gray-200">
        @foreach($versiones as $ver)
            <li class="py-2 flex justify-between items-center">
                <span>Versión {{ str_pad($ver->version, 4, '0', STR_PAD_LEFT) }}</span>
                <small class="text-gray-500">{{ $ver->created_at->format('d/m/Y H:i') }}</small>
            </li>
        @endforeach
    </ul>

    @if(auth()->user()->rol === 'admin')
        <hr class="my-6">

        <h4 class="text-xl font-semibold text-[#155f82] mb-3">👥 Usuarios con Acceso</h4>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200 rounded text-sm">
                <thead class="bg-[#155f82] text-white">
                    <tr>
                        <th class="px-4 py-2 text-left">Usuario</th>
                        <th class="px-4 py-2 text-left">Correo</th>
                        <th class="px-4 py-2 text-left">Permiso</th>
                        <th class="px-4 py-2 text-left">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($permisos as $permiso)
                        <tr class="border-t hover:bg-gray-50">
                            <td class="px-4 py-2">{{ $permiso->usuario->nombre }} {{ $permiso->usuario->apellido }}</td>
                            <td class="px-4 py-2">{{ $permiso->usuario->correo }}</td>
                            <td class="px-4 py-2">
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
                            <td class="px-4 py-2 flex gap-2">
                                <a href="{{ route('permisos.edit', $permiso->id_permiso) }}" class="text-blue-600 hover:underline">Editar</a>

                                <form action="{{ route('permisos.destroy', $permiso->id_permiso) }}" method="POST"
                                      onsubmit="return confirm('¿Estás seguro de quitar este permiso?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
