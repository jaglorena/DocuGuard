@extends('Usuario.layouts.app')

@section('content')
<div class="max-w-xl mx-auto mt-10 bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold text-[#155f82] mb-4">Editar Permiso</h2>

    <form action="{{ route('permisos.update', $permiso->id_permiso) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block font-semibold mb-1">Nivel de Acceso</label>
            <select name="nivel_acceso" class="w-full border px-3 py-2 rounded" required>
                <option value="lectura" {{ $permiso->nivel_acceso == 'lectura' ? 'selected' : '' }}>Lectura</option>
                <option value="escritura" {{ $permiso->nivel_acceso == 'escritura' ? 'selected' : '' }}>Escritura</option>
                <option value="eliminacion" {{ $permiso->nivel_acceso == 'eliminacion' ? 'selected' : '' }}>Eliminación</option>
            </select>
        </div>

        <button type="submit" class="bg-[#155f82] text-white px-4 py-2 rounded hover:bg-[#134d6e]">Actualizar</button>
    </form>
</div>
@endsection
