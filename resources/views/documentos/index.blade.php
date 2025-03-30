@extends('Usuario.layouts.app')

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
            <h3 class="text-xl font-semibold text-gray-800">{{ $documento->titulo }}</h3>
            <p class="text-gray-600">{{ $documento->descripcion }}</p>
            <p class="text-sm text-gray-500">
                📌 Versión: {{ $documento->version }} |
                Estado: {{ ucfirst($documento->estado) }} |
                📅 Subido el {{ \Carbon\Carbon::parse($documento->fecha_subida)->format('d/m/Y') }}
            </p>

            <p class="text-sm text-gray-500">
                👤 Subido por: {{ $documento->usuario->nombre }} {{ $documento->usuario->apellido }} ({{ $documento->usuario->correo }})
            </p>

            {{-- Acciones disponibles según permisos o si es administrador --}}
            <div class="mt-3 flex flex-wrap gap-3">
                @if(auth()->user()->tienePermiso($documento->id_documento, 'lectura') || strtolower(auth()->user()->rol) === 'administrador')
                    <a href="{{ route('documentos.show', $documento->id_documento) }}"
                    class="text-blue-600 hover:underline">🔍 Ver</a>
                @endif

                @if(auth()->user()->tienePermiso($documento->id_documento, 'escritura') || strtolower(auth()->user()->rol) === 'administrador')
                    <a href="{{ route('documentos.edit', $documento->id_documento) }}"
                    class="text-yellow-600 hover:underline">✏️ Editar</a>
                @endif

                @if(auth()->user()->tienePermiso($documento->id_documento, 'eliminacion') || strtolower(auth()->user()->rol) === 'administrador')
                    <form action="{{ route('documentos.destroy', $documento->id_documento) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('¿Estás seguro?')">
                            🗑️ Eliminar
                        </button>
                    </form>
                @endif
            </div>
            {{-- Mostrar permisos --}}
            <div class="mt-2 text-sm text-gray-800">
                @if(strtolower(auth()->user()->rol) === 'administrador')
                    <p class="font-semibold text-[#155f82]">👥 Permisos por usuario:</p>
                    <ul class="list-disc ml-5">
                        @php
                            $agrupados = $documento->permisos->groupBy('id_usuario');
                        @endphp

                        @foreach($agrupados as $usuarioId => $permisosUsuario)
                            <li>
                                {{ $permisosUsuario->first()->usuario->nombre }} {{ $permisosUsuario->first()->usuario->apellido }}:
                                @foreach($permisosUsuario as $permiso)
                                    @php
                                        $icon = match($permiso->nivel_acceso) {
                                            'lectura' => '📖 Lectura',
                                            'escritura' => '✏️ Escritura',
                                            'eliminacion' => '🗑️ Eliminación',
                                            default => $permiso->nivel_acceso
                                        };

                                        $color = match($permiso->nivel_acceso) {
                                            'lectura' => 'text-green-600',
                                            'escritura' => 'text-yellow-600',
                                            'eliminacion' => 'text-red-600',
                                            default => 'text-gray-600'
                                        };
                                    @endphp
                                    <span class="ml-1 {{ $color }}">{{ $icon }}</span>
                                @endforeach
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    @endforeach
</div>
@endsection

