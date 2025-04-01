@extends('Usuario.layouts.app')

@section('content')
<div class="max-w-xl mx-auto mt-10 bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold text-[#155f82] mb-4">Asignar Permiso</h2>

    <form action="{{ route('permisos.store') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label class="block font-semibold mb-1">Usuario</label>
            <select name="id_usuario" class="w-full border px-3 py-2 rounded" required>
                <option value="">Seleccione un usuario</option>
                @foreach ($usuarios as $usuario)
                    <option value="{{ $usuario->id_usuario }}">{{ $usuario->nombre }} {{ $usuario->apellido }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block font-semibold mb-1">Documento</label>
            <select name="id_documento[]" multiple required class="w-full border px-3 py-2 rounded">
                <option value="">Seleccione un documento</option>
                @foreach ($documentos as $documento)
                    <option value="{{ $documento->id_documento }}">
                        {{ preg_replace('/\[grupo=.*?\]/', '', $documento->titulo) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block font-semibold mb-1">Nivel de Acceso</label>
            <div class="flex gap-4">
                <label><input type="checkbox" name="nivel_acceso[]" value="lectura"> Lectura</label>
                <label><input type="checkbox" name="nivel_acceso[]" value="escritura"> Escritura</label>
                <label><input type="checkbox" name="nivel_acceso[]" value="eliminacion"> Eliminación</label>
            </div>
        </div>

        <button type="submit" class="bg-[#155f82] text-white px-4 py-2 rounded hover:bg-[#134d6e]">Asignar</button>
    </form>
</div>
@endsection
