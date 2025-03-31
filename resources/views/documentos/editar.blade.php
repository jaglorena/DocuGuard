@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto mt-10 bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold text-[#155f82] mb-6 flex items-center gap-2">Editar Documento</h2>

    <form action="{{ route('documentos.update', $documento->id_documento) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- Título --}}
        <div>
            <label for="titulo" class="block text-sm font-medium text-gray-700">Título</label>
            <input type="text" name="titulo" id="titulo" value="{{ $documento->titulo }}" class="mt-1 block w-full border rounded px-3 py-2 shadow-sm focus:ring focus:ring-[#155f82]" required>
        </div>

        {{-- Descripción --}}
        <div>
            <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripción</label>
            <textarea name="descripcion" id="descripcion" rows="3" class="mt-1 block w-full border rounded px-3 py-2 shadow-sm focus:ring focus:ring-[#155f82]">{{ $documento->descripcion }}</textarea>
        </div>

        {{-- Estado --}}
        <div>
            <label for="estado" class="block text-sm font-medium text-gray-700">Estado</label>
            <select name="estado" id="estado" class="mt-1 block w-full border rounded px-3 py-2 shadow-sm focus:ring focus:ring-[#155f82]" required>
                <option value="borrador" {{ $documento->estado === 'borrador' ? 'selected' : '' }}>Borrador</option>
                <option value="activo" {{ $documento->estado === 'activo' ? 'selected' : '' }}>Publicado</option>
                <option value="archivado" {{ $documento->estado === 'archivado' ? 'selected' : '' }}>Archivado</option>
            </select>
        </div>

        {{-- Permisos para ADMIN --}}
        @if(auth()->user()->rol === 'Administrador')
        <div class="border-t pt-6">
            <h4 class="text-lg font-bold mb-4 text-[#155f82]">🔐 Permisos Asignados por Usuario</h4>

            @foreach ($usuarios as $usuario)
            <div class="mb-4">
                <p class="font-semibold text-gray-800">{{ $usuario->nombre }} {{ $usuario->apellido }}</p>
                <div class="flex gap-4 mt-2 text-sm">
                    <label class="flex items-center gap-1">
                        <input type="checkbox" name="permisos[{{ $usuario->id_usuario }}][]" value="lectura"
                            {{ in_array('lectura', $permisosActuales[$usuario->id_usuario] ?? []) ? 'checked' : '' }}>
                        Lectura
                    </label>
                    <label class="flex items-center gap-1">
                        <input type="checkbox" name="permisos[{{ $usuario->id_usuario }}][]" value="escritura"
                            {{ in_array('escritura', $permisosActuales[$usuario->id_usuario] ?? []) ? 'checked' : '' }}>
                        Escritura
                    </label>
                    <label class="flex items-center gap-1">
                        <input type="checkbox" name="permisos[{{ $usuario->id_usuario }}][]" value="eliminacion"
                            {{ in_array('eliminacion', $permisosActuales[$usuario->id_usuario] ?? []) ? 'checked' : '' }}>
                        Eliminación
                    </label>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        {{-- Botón --}}
        <div class="pt-6 border-t text-center">
            <button type="submit" class="bg-[#155f82] text-white px-6 py-2 rounded hover:bg-[#0d4866] transition">
                Actualizar Documento
            </button>
        </div>
    </form>
</div>
@endsection
