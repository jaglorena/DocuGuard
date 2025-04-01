@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto mt-8 bg-white p-6 rounded shadow">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-[#155f82]">{{ preg_replace('/\[grupo=.*?\]/', '', $documento->titulo) }}</h2>
    </div>

    @php
        use App\Enums\EstadoDocumento;

        $estilosEstado = [
            EstadoDocumento::Activo->value => 'bg-green-100 text-green-700',
            EstadoDocumento::Archivado->value => 'bg-gray-400 text-white',
            EstadoDocumento::EnRevision->value => 'bg-yellow-100 text-yellow-700',
        ];

        $estado = $documento->estado;
        $clase = $estilosEstado[$estado] ?? 'bg-gray-200 text-gray-700';
    @endphp


    <div class="mb-4">
        <p class="text-gray-700"><strong>Descripción:</strong> {{ $documento->descripcion }}</p>
        <p class="text-gray-700"><strong>Estado:</strong>
            <span class="inline-block px-2 py-1 rounded text-sm font-semibold {{ $clase }}">
                {{ ucfirst($estado) }}
            </span>
        </p>
        <p class="text-gray-700"><strong>Versión:</strong> {{ str_pad($documento->version, 3, '0', STR_PAD_LEFT) }}</p>
    </div>

    {{-- Mostrar solo la(s) versión(es) que el usuario tiene asignadas --}}
    @if(strtolower(auth()->user()->rol) !== 'administrador')
        @php
            \Log::info("Versiones: ");
            \Log::info($versiones);

            $versionesConPermiso = $versiones->filter(function ($v) {
                return auth()->user()->tienePermiso($v->id_documento, 'lectura') && $v->ruta_archivo;
            });
            \Log::info("Versiones con permisos: ");
            \Log::info($versionesConPermiso);
        @endphp

        @if($versionesConPermiso->isNotEmpty())
            <div class="mt-6">
                <h3 class="text-lg font-semibold text-[#155f82] mb-4">📄 Documento(s) Asignado(s)</h3>

                @foreach($versionesConPermiso as $ver)
                    <div class="mb-8 border border-gray-200 p-4 rounded shadow">
                        <p class="font-semibold text-gray-700 mb-2">
                            Versión {{ str_pad($ver->version, 3, '0', STR_PAD_LEFT) }} — Estado:
                            <span class="font-medium">{{ ucfirst($ver->estado) }}</span>
                        </p>

                        <iframe src="{{ asset('storage/' . $ver->ruta_archivo) }}"
                                width="100%" height="500px" class="border rounded shadow"></iframe>

                        <div class="mt-3 flex flex-wrap gap-3">
                            {{-- Descargar --}}
                            <a href="{{ asset('storage/' . $ver->ruta_archivo) }}" target="_blank"
                            class="inline-flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                                📥 Descargar
                            </a>

                            {{-- Editar si tiene permiso de escritura --}}
                            @if(auth()->user()->tienePermiso($ver->id_documento, 'escritura'))
                                <a href="{{ route('documentos.edit', $ver->id_documento) }}"
                                class="inline-flex items-center gap-2 bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600 transition">
                                    ✏️ Editar
                                </a>
                            @endif

                            {{-- Eliminar si tiene permiso de eliminación --}}
                            @if(auth()->user()->tienePermiso($ver->id_documento, 'eliminacion'))
                                <form action="{{ route('documentos.destroy', $ver->id_documento) }}" method="POST"
                                    onsubmit="return confirm('¿Estás seguro que deseas eliminar esta versión?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex items-center gap-2 bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition">
                                        🗑️ Eliminar
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-sm text-gray-500 mt-4">No tenés permisos de lectura sobre ninguna versión de este documento.</p>
        @endif
    @endif


    <hr class="my-6">

    @if(strtolower(auth()->user()->rol) === 'administrador')
        <hr class="my-6">

        <h4 class="text-lg font-semibold text-[#155f82] mb-3">Historial de Versiones</h4>
        <ul class="divide-y divide-gray-200 space-y-4" id="historial">
            @forelse($versiones as $ver)
                <li class="py-4 px-4 bg-gray-50 rounded shadow-sm hover:shadow-md transition duration-200">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-base font-bold text-[#155f82]">
                            Versión {{ str_pad($ver->version, 3, '0', STR_PAD_LEFT) }}
                        </span>
                        <small class="text-gray-500">{{ $ver->created_at->format('d/m/Y H:i') }}</small>
                    </div>

                    {{-- Descripción y estado --}}
                    <div class="text-sm text-gray-700 space-y-1">
                        <p><strong>📝 Descripción:</strong> {{ $ver->descripcion }}</p>
                        <p>
                            <strong>📌 Estado:</strong>
                            <span class="inline-block px-2 py-1 rounded text-xs font-semibold
                                {{ $ver->estado === 'activo' ? 'bg-green-100 text-green-700' :
                                ($ver->estado === 'borrador' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-200 text-gray-700') }}">
                                {{ ucfirst($ver->estado) }}
                            </span>
                        </p>
                    </div>

                    {{-- Usuarios con acceso --}}
                    @php
                        $usuariosAsignados = $ver->permisos->groupBy('id_usuario');
                    @endphp

                    @if($usuariosAsignados->count())
                        <div class="mt-3 text-sm text-gray-700">
                            <strong>👥 Usuarios con acceso:</strong>
                            <ul class="list-disc ml-6 mt-1 space-y-1">
                                @foreach($usuariosAsignados as $usuarioId => $permisosUsuario)
                                    <li>
                                        {{ $permisosUsuario->first()->usuario->nombre }} {{ $permisosUsuario->first()->usuario->apellido }}:
                                        @foreach($permisosUsuario as $permiso)
                                            @php
                                                $icon = match($permiso->nivel_acceso) {
                                                    'lectura' => 'Lectura 📖',
                                                    'escritura' => 'Escritura ✏️',
                                                    'eliminacion' => 'Eliminar 🗑️',
                                                    default => '🔐'
                                                };
                                            @endphp
                                            <span class="ml-1">{{ $icon }}</span>
                                        @endforeach
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @else
                        <p class="mt-2 text-sm text-gray-500 italic">Sin usuarios asignados a esta versión.</p>
                    @endif

                    {{-- Botón editar --}}
                    @if(strtolower(auth()->user()->rol) === 'administrador')
                        <div class="mt-3">
                            <a href="{{ route('documentos.edit', $ver->id_documento) }}"
                            class="inline-flex items-center gap-1 text-yellow-600 hover:underline text-sm">
                                ✏️ Editar esta versión
                            </a>
                        </div>
                    @endif

                    {{-- Botón eliminar --}}
                    @if(auth()->user()->tienePermiso($documento->id_documento, 'eliminacion') || strtolower(auth()->user()->rol) === 'administrador')
                        <form action="{{ route('documentos.destroy', $documento->id_documento) }}" method="POST" class="inline-block mt-2">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline text-sm" onclick="return confirm('¿Estás seguro?')">
                                🗑️ Eliminar
                            </button>
                        </form>
                    @endif

                    {{-- Ver o Descargar --}}
                    @if($ver->ruta_archivo)
                        <div class="mt-3">
                            <a href="{{ asset('storage/' . $ver->ruta_archivo) }}"
                            target="_blank"
                            class="inline-flex items-center gap-2 bg-[#155f82] text-white px-4 py-2 rounded hover:bg-[#0d4866] transition">
                                Ver o Descargar esta Versión
                            </a>
                        </div>
                    @endif
                </li>
            @empty
                <li class="py-2 text-gray-500">Sin versiones registradas.</li>
            @endforelse
        </ul>
    @endif

    @if(auth()->user()->rol === 'admin')
        <hr class="my-6">

        <h4 class="text-xl font-semibold text-[#155f82] mb-3">👥 Usuarios con Acceso</h4>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200 rounded text-sm">
                <thead class="bg-[#155f82] text-white">
                    <tr>
                        <th class="px-4 py-2 text-left">Usuario</th>
                        <th class="px-4 py-2 text-left">Correo</th>
                        <th class="px-4 py-2 text-left">Permiso</th>
                        <th class="px-4 py-2 text-left">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($permisos as $permiso)
                        <tr class="border-t hover:bg-gray-50">
                            <td class="px-4 py-2">{{ $permiso->usuario->nombre }} {{ $permiso->usuario->apellido }}</td>
                            <td class="px-4 py-2">{{ $permiso->usuario->correo }}</td>
                            <td class="px-4 py-2">
                                @php
                                    $color = match($permiso->nivel_acceso) {
                                        'lectura' => 'bg-blue-100 text-blue-700',
                                        'escritura' => 'bg-yellow-100 text-yellow-700',
                                        'eliminacion' => 'bg-red-100 text-red-700',
                                        default => 'bg-gray-100 text-gray-700'
                                    };
                                @endphp
                                <span class="px-2 py-1 rounded text-sm font-semibold {{ $color }}">
                                    {{ ucfirst($permiso->nivel_acceso) }}
                                </span>
                            </td>
                            <td class="px-4 py-2 flex gap-2">
                                <a href="{{ route('permisos.edit', $permiso->id_permiso) }}" class="text-blue-600 hover:underline">Editar</a>

                                <form action="{{ route('permisos.destroy', $permiso->id_permiso) }}" method="POST"
                                      onsubmit="return confirm('¿Estás seguro de quitar este permiso?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
