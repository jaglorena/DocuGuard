@extends('Usuario.layouts.app')

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
            @foreach ($permisos as $permiso)
                <tr class="border-t hover:bg-gray-100">
                    <td class="p-2">{{ $permiso->usuario->nombre }} {{ $permiso->usuario->apellido }}</td>
                    <td class="p-2">{{ $permiso->documento->titulo }}</td>
                    <td class="p-2 capitalize">{{ $permiso->nivel_acceso }}</td>
                    <td class="p-2">
                        <a href="{{ route('permisos.edit', $permiso->id_permiso) }}" class="text-blue-600 hover:underline">Editar</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
