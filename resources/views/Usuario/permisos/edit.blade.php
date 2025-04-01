@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto mt-10 bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold text-[#155f82] mb-4">Editar Permisos</h2>

    <form action="{{ route('permisos.update', $permiso->id_permiso) }}" method="POST">
        @csrf
        @method('PUT')

        <p class="mb-2 text-sm text-gray-600">
            Usuario: <strong>{{ $permiso->usuario->nombre }} {{ $permiso->usuario->apellido }}</strong><br>
            Documento: <strong>{{ $permiso->documento->titulo }}</strong>
        </p>

        <div class="mb-4">
            <label class="block font-semibold mb-1">Selecciona los niveles de acceso:</label>
            @php
                // Obtener todos los permisos actuales para este usuario y documento
                $permisosUsuario = $permiso->usuario->permisos()
                    ->where('id_documento', $permiso->id_documento)
                    ->pluck('nivel_acceso')
                    ->toArray();
            @endphp

            <div class="flex gap-4 mt-2">
                <label><input type="checkbox" name="nivel_acceso[]" value="lectura"
                    {{ in_array('lectura', $permisosUsuario) ? 'checked' : '' }}> Lectura</label>

                <label><input type="checkbox" name="nivel_acceso[]" value="escritura"
                    {{ in_array('escritura', $permisosUsuario) ? 'checked' : '' }}> Escritura</label>

                <label><input type="checkbox" name="nivel_acceso[]" value="eliminacion"
                    {{ in_array('eliminacion', $permisosUsuario) ? 'checked' : '' }}> Eliminación</label>
            </div>
        </div>

        <button type="submit" class="bg-[#155f82] text-white px-4 py-2 rounded hover:bg-[#134d6e]">Actualizar</button>
    </form>
</div>
@endsection
