@extends('layouts.app')

@section('content')
<div class="bg-white p-6 rounded shadow max-w-5xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-[#155f82]">Gestión de Documentos</h2>

        @if(strtolower(auth()->user()->rol) === 'administrador')
            <div class="flex gap-2">
                <a href="{{ route('documentos.create') }}"
                class="inline-flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                    <span>📄</span> Nuevo Documento
                </a>

                <a href="{{ route('permisos.index') }}"
                class="inline-flex items-center gap-2 bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
                    <span>🔐</span> Ver Permisos
                </a>
            </div>
        @endif

        {{-- Mostrar el rol actual del usuario --}}
        <p class="text-sm text-red-600">Rol actual: {{ auth()->user()->rol }}</p>
    </div>

    @if(session('success'))
        <div id="alert" class="bg-green-100 border border-green-400 text-green-800 px-4 py-2 rounded mb-4 transition">
            {{ session('success') }}
        </div>

        <script>
            setTimeout(() => {
                const alert = document.getElementById('alert');
                if (alert) {
                    alert.style.opacity = 0;
                    setTimeout(() => alert.remove(), 500);
                }
            }, 3000);
        </script>
    @endif

    @foreach ($documentos as $documento)
        @php
            $permisos = auth()->user()->permisos()
                ->where('id_documento', $documento->id_documento)
                ->pluck('nivel_acceso')
                ->toArray();
        @endphp

        <div class="border-l-4 p-4 rounded mb-4 shadow {{ auth()->user()->colorPermiso($documento->id_documento) }}">
            <h3 class="text-xl font-semibold text-gray-800">{{ preg_replace('/\[grupo=.*?\]/', '', $documento->titulo) }}</h3>
            <p class="text-gray-600">{{ $documento->descripcion }}</p>
            <p class="text-sm text-gray-500">
                📌 Versión: {{ str_pad($documento->version, 3, '0', STR_PAD_LEFT) }} |
                Estado: {{ ucfirst($documento->estado) }} |
                📅 Subido el {{ \Carbon\Carbon::parse($documento->fecha_subida)->format('d/m/Y') }}
            </p>

            <p class="text-sm text-gray-500">
                👤 Subido por: {{ $documento->usuario->nombre }} {{ $documento->usuario->apellido }} ({{ $documento->usuario->correo }})
            </p>

            @if(strtolower(auth()->user()->rol) !== 'administrador' && !empty($permisos))
                <p class="text-sm text-indigo-600 mt-1">
                    🔐 Tus permisos asignados: {{ implode(', ', $permisos) }}
                </p>
            @endif

            {{-- Acciones disponibles según permisos o si es administrador --}}
            <div class="mt-3 flex flex-wrap gap-3">
                @if(auth()->user()->tienePermiso($documento->id_documento, 'lectura') || strtolower(auth()->user()->rol) === 'administrador')
                    <a href="{{ route('documentos.show', $documento->id_documento) }}"
                    class="text-blue-600 hover:underline">🔍 Ver</a>

                    @if(auth()->user()->rol === 'administrador')
                        <a href="{{ route('documentos.show', $documento->id_documento) }}#historial"
                        class="text-gray-700 hover:underline">📜 Ver Historial</a>
                    @endif
                @endif
            </div>
        </div>
    @endforeach
</div>
@endsection
