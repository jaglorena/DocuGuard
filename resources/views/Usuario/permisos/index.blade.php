@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto mt-10 bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold text-[#155f82] mb-6">Permisos Asignados</h2>

    <a href="{{ route('permisos.create') }}" class="mb-4 inline-block bg-[#155f82] text-white px-4 py-2 rounded hover:bg-[#134d6e]">Asignar Nuevo Permiso</a>

    <table class="w-full text-left border">
        <thead>
            <tr class="bg-[#155f82] text-white">
                <th class="p-2">Usuario</th>
                <th class="p-2">Documento</th>
                <th class="p-2">Nivel de Acceso</th>
                <th class="p-2">Acciones</th>
            </tr>
        </thead>
        <tbody>
    @php
        // Agrupamos primero por usuario
        $usuariosAgrupados = $permisos->groupBy('id_usuario');
    @endphp

    @foreach ($usuariosAgrupados as $usuarioId => $permisosPorUsuario)
        @php
            $usuario = $permisosPorUsuario->first()->usuario;
            $documentosAgrupados = $permisosPorUsuario->groupBy('id_documento');
        @endphp

        {{-- Fila de resumen del usuario --}}
        <tr class="bg-gray-100">
            <td colspan="4" class="p-2 font-bold text-[#155f82]">
                👤 {{ $usuario->nombre }} {{ $usuario->apellido }} —
                <span class="text-sm text-gray-600">Documentos asignados: {{ $documentosAgrupados->count() }}</span>
            </td>
        </tr>

        {{-- Fila por cada documento asignado --}}
        @foreach ($documentosAgrupados as $documentoId => $permisosGrupo)
            @php
                $documento = $permisosGrupo->first()->documento;
                $niveles = $permisosGrupo->pluck('nivel_acceso')->toArray();
            @endphp
            <tr class="border-t hover:bg-gray-50">
                <td class="p-2"></td>
                <td class="p-2">{{ $documento->titulo }}</td>
                <td class="p-2">
                    @foreach ($niveles as $nivel)
                        @php
                            $color = match($nivel) {
                                'lectura' => 'bg-blue-100 text-blue-700',
                                'escritura' => 'bg-yellow-100 text-yellow-700',
                                'eliminacion' => 'bg-red-100 text-red-700',
                                default => 'bg-gray-100 text-gray-700'
                            };
                        @endphp
                        <span class="px-2 py-1 rounded text-sm font-semibold {{ $color }}">
                            {{ ucfirst($nivel) }}
                        </span>
                    @endforeach
                </td>
                <td class="p-2">
                    <a href="{{ route('permisos.edit', $permisosGrupo->first()->id_permiso) }}"
                       class="text-blue-600 hover:underline">Editar</a>
                </td>
            </tr>
        @endforeach
    @endforeach
</tbody>

    </table>
</div>
@endsection
