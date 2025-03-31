@extends('Usuario.layouts.app')

@section('content')
<div class="bg-white p-6 rounded shadow max-w-6xl mx-auto">
    <h2 class="text-2xl font-bold text-[#155f82] mb-6">Gestión de Usuarios</h2>

    <a href="{{ route('usuarios.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block hover:bg-blue-600">+ Nuevo Usuario</a>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <table class="w-full table-auto text-sm">
        <thead class="bg-gray-100 text-left">
            <tr>
                <th class="p-2">Nombre</th>
                <th class="p-2">Apellido</th>
                <th class="p-2">Correo</th>
                <th class="p-2">Rol</th>
                <th class="p-2">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($usuarios as $usuario)
                <tr class="border-b">
                    <td class="p-2">{{ $usuario->nombre }}</td>
                    <td class="p-2">{{ $usuario->apellido }}</td>
                    <td class="p-2">{{ $usuario->correo }}</td>
                    <td class="p-2">{{ $usuario->rol }}</td>
                    <td class="p-2 space-x-2">
                        <a href="{{ route('usuarios.edit', $usuario->id_usuario) }}" class="text-blue-600 hover:underline">Editar</a>
                        <a href="{{ route('usuarios.cambiar-password.form', $usuario->id_usuario) }}" class="text-yellow-600 hover:underline">Cambiar Contraseña</a>
                        <form action="{{ route('usuarios.destroy', $usuario->id_usuario) }}" method="POST" class="inline">
                            @csrf @method('DELETE')
                            <button class="text-red-600 hover:underline" onclick="return confirm('¿Eliminar este usuario?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
