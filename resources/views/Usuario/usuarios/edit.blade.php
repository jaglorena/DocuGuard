@extends('layouts.app')

@section('content')
<div class="bg-white p-6 rounded shadow max-w-xl mx-auto">
    <h2 class="text-xl font-bold text-[#155f82] mb-4">Editar Usuario</h2>

    <form method="POST" action="{{ route('usuarios.update', $usuario->id_usuario) }}">
        @csrf @method('PUT')

        <input type="text" name="nombre" value="{{ $usuario->nombre }}" class="w-full mb-3 p-2 border rounded" required>
        <input type="text" name="apellido" value="{{ $usuario->apellido }}" class="w-full mb-3 p-2 border rounded" required>
        <input type="email" name="correo" value="{{ $usuario->correo }}" class="w-full mb-3 p-2 border rounded" required>

        <select name="rol" class="w-full mb-4 p-2 border rounded" required>
            <option value="Usuario" {{ $usuario->rol == 'Usuario' ? 'selected' : '' }}>Usuario</option>
            <option value="Administrador" {{ $usuario->rol == 'Administrador' ? 'selected' : '' }}>Administrador</option>
        </select>

        <button type="submit" class="bg-[#155f82] text-white px-4 py-2 rounded hover:bg-blue-700">Actualizar</button>
    </form>
</div>
@endsection
