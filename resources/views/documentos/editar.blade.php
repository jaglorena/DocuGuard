@extends('Usuario.layouts.app')

@section('content')
<div class="max-w-4xl mx-auto mt-10 bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold text-[#155f82] mb-6 flex items-center gap-2">Editar Documento</h2>

    <form action="{{ route('documentos.update', $documento->id_documento) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- Título --}}
        <div>
            <label for="titulo" class="block text-sm font-medium text-gray-700">Título</label>
            @php
                $tituloLimpio = preg_replace('/\[grupo=.*?\]/', '', $documento->titulo);
            @endphp

            <input type="text" name="titulo" id="titulo" value="{{ $tituloLimpio }}" class="mt-1 block w-full border rounded px-3 py-2 shadow-sm focus:ring focus:ring-[#155f82]" required>
        </div>

        {{-- Descripción --}}
        <div>
            <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripción</label>
            <textarea name="descripcion" id="descripcion" rows="3" class="mt-1 block w-full border rounded px-3 py-2 shadow-sm focus:ring focus:ring-[#155f82]">{{ $documento->descripcion }}</textarea>
        </div>

        {{-- Estado --}}
        @php
            use App\Enums\EstadoDocumento;
        @endphp

        <label for="estado" class="block text-sm font-medium text-gray-700">Estado</label>
        <select name="estado" id="estado" class="...">
            @foreach(\App\Enums\EstadoDocumento::cases() as $estado)
                <option value="{{ $estado->value }}" {{ old('estado', $documento->estado ?? '') === $estado->value ? 'selected' : '' }}>
                    {{ ucfirst($estado->value) }}
                </option>
            @endforeach
        </select>

        {{-- Botón --}}
        <div class="pt-6 border-t text-center">
            <button type="submit" class="bg-[#155f82] text-white px-6 py-2 rounded hover:bg-[#0d4866] transition">
                Actualizar Documento
            </button>
        </div>
    </form>
</div>
@endsection
