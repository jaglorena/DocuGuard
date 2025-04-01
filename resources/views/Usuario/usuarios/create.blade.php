@extends('layouts.app')

@section('content')
<div class="bg-white p-6 rounded shadow max-w-xl mx-auto">
    <h2 class="text-xl font-bold text-[#155f82] mb-4">Registrar Nuevo Usuario</h2>

    <form method="POST" action="{{ route('usuarios.store') }}">
        @csrf

        <input type="text" name="nombre" placeholder="Nombre" class="w-full mb-3 p-2 border rounded" required>
        <input type="text" name="apellido" placeholder="Apellido" class="w-full mb-3 p-2 border rounded" required>
        <input type="email" name="correo" placeholder="Correo electrónico" class="w-full mb-3 p-2 border rounded" required>
        <input type="password" name="password" placeholder="Contraseña" class="w-full mb-3 p-2 border rounded" required>

        <select name="rol" class="w-full mb-4 p-2 border rounded" required>
            <option value="Usuario">Usuario</option>
            <option value="Administrador">Administrador</option>
        </select>

        <button type="submit" class="bg-[#155f82] text-white px-4 py-2 rounded hover:bg-blue-700">Guardar</button>
    </form>
</div>
@endsection
