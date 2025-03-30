@extends('Usuario.layouts.app')

@section('content')
<div class="max-w-md mx-auto bg-white p-8 rounded shadow">
    <h2 class="text-2xl font-bold mb-6 text-[#155f82]">Crear Cuenta</h2>
    <form action="{{ route('register') }}" method="POST">
        @csrf

        <input type="text" name="nombre" placeholder="Nombre" value="{{ old('nombre') }}"
               class="w-full mb-3 px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-[#155f82]">
        <input type="text" name="apellido" placeholder="Apellido" value="{{ old('apellido') }}"
               class="w-full mb-3 px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-[#155f82]">
        <input type="email" name="correo" placeholder="Correo Electrónico" value="{{ old('correo') }}"
               class="w-full mb-3 px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-[#155f82]">
        <input type="password" name="password" placeholder="Contraseña"
               class="w-full mb-3 px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-[#155f82]">
        <input type="password" name="password_confirmation" placeholder="Confirmar Contraseña"
               class="w-full mb-4 px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-[#155f82]">

        <button type="submit" class="w-full bg-[#155f82] text-white py-2 rounded hover:bg-[#104e6c]">Registrarse</button>
    </form>

    <p class="text-center text-sm mt-4">
        ¿Ya tienes cuenta? <a href="{{ route('login') }}" class="text-[#155f82] underline">Inicia sesión</a>
    </p>
</div>
@endsection
