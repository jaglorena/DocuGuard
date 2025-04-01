@extends('Usuario.layouts.app')

@section('content')
<div class="bg-white p-8 rounded shadow max-w-4xl mx-auto">
    <h2 class="text-2xl font-bold text-[#155f82] mb-6">Subir Nuevo Documento</h2>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 p-4 rounded mb-4">
            <strong class="block mb-2">Errores:</strong>
            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('documentos.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf

        {{-- Título --}}
        <div>
            <label for="titulo" class="block font-medium text-gray-700">Título</label>
            <input type="text" name="titulo" id="titulo"
                value="{{ old('titulo', $titulo ?? '') }}"
                class="mt-1 w-full border border-gray-300 rounded px-3 py-2 shadow-sm focus:ring-[#155f82] focus:border-[#155f82]" required>
        </div>

        {{-- Grupo existente (selección) --}}
        @if(!empty($documentosExistentes) && count($documentosExistentes))
        <div>
                <label for="grupo_existente" class="block font-medium text-gray-700">Agregar como versión de documento existente</label>
                <select name="grupo_existente" id="grupo_existente" class="mt-1 w-full border border-gray-300 rounded px-3 py-2 shadow-sm">
                    <option value="">- Elegir grupo de Documentos -</option>
                    @foreach($documentosExistentes as $grupo => $doc)
                        @php
                            $grupoId = $grupo;
                            $tituloEditable = trim(preg_replace('/\[grupo=.*?\]/', '', $doc->titulo));
                        @endphp
                        <option value="{{ $grupoId }}">
                            {{ $tituloEditable }}
                        </option>
                    @endforeach
                </select>
                <p class="text-xs text-gray-500 mt-1">Seleccioná un documento existente si querés agregar una nueva versión.</p>
            </div>
        @endif

        <div>
            <label for="descripcion" class="block font-medium text-gray-700">Descripción</label>
            <textarea name="descripcion" id="descripcion" rows="3" class="mt-1 w-full border border-gray-300 rounded px-3 py-2 shadow-sm focus:ring-[#155f82] focus:border-[#155f82]"></textarea>
        </div>

        <div>
            <label for="archivo" class="block font-medium text-gray-700">Archivo</label>
            <input type="file" name="archivo" id="archivo" class="mt-1 w-full" required>
            <p class="text-xs text-gray-500 mt-1">Formatos permitidos: pdf, doc, docx, txt.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="version" class="block font-medium text-gray-700">Versión</label>
                <input type="text" name="version" id="version" 
                    value="{{ old('version', $nuevaVersion ?? '') }}" 
                    class="mt-1 w-full border border-gray-300 rounded px-3 py-2 shadow-sm"
                    readonly required>
            </div>

            @php
                use App\Enums\EstadoDocumento;
            @endphp

            <div>
                <label for="estado" class="block font-medium text-gray-700">Estado</label>
                <select name="estado" id="estado" class="...">
                    @foreach(\App\Enums\EstadoDocumento::cases() as $estado)
                        <option value="{{ $estado->value }}" {{ old('estado', $documento->estado ?? '') === $estado->value ? 'selected' : '' }}>
                            {{ ucfirst($estado->value) }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        @if(auth()->user()->rol === 'administrador')
            <div>
                <h3 class="text-lg font-semibold mb-2 text-[#155f82]">🔐 Asignar Permisos a Usuarios</h3>
                <div class="space-y-3">
                    @foreach ($usuarios as $usuario)
                        <div class="border p-3 rounded bg-gray-50">
                            <p class="font-medium text-gray-800">{{ $usuario->nombre }} {{ $usuario->apellido }}</p>
                            <div class="flex gap-6 mt-2 text-sm">
                                <label><input type="checkbox" name="permisos[{{ $usuario->id_usuario }}][]" value="lectura"> Lectura</label>
                                <label><input type="checkbox" name="permisos[{{ $usuario->id_usuario }}][]" value="escritura"> Escritura</label>
                                <label><input type="checkbox" name="permisos[{{ $usuario->id_usuario }}][]" value="eliminacion"> Eliminación</label>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="flex justify-end gap-2">
            <a href="{{ route('documentos.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">Cancelar</a>
            <button type="submit" class="bg-[#155f82] text-white px-6 py-2 rounded hover:bg-[#134d6e] transition">Subir</button>
        </div>
    </form>
</div>
@endsection
