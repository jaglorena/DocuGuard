@extends('Usuario.layouts.app')

@section('content')
<div class="bg-white p-6 rounded shadow max-w-xl mx-auto">
    <h2 class="text-xl font-bold text-[#155f82] mb-4">Cambiar Contraseña</h2>

    <form method="POST" action="{{ route('usuarios.cambiar-password', $usuario->id_usuario) }}">
        @csrf @method('PUT')

        <input type="password" name="password" placeholder="Nueva contraseña" class="w-full mb-3 p-2 border rounded" required>
        <input type="password" name="password_confirmation" placeholder="Confirmar contraseña" class="w-full mb-4 p-2 border rounded" required>

        <button type="submit" class="bg-[#155f82] text-white px-4 py-2 rounded hover:bg-blue-700">Cambiar</button>
    </form>
</div>
@endsection
