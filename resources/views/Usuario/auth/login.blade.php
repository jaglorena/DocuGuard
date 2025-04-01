@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto bg-white p-8 rounded shadow">
    <h2 class="text-2xl font-bold mb-6 text-[#155f82]">Iniciar Sesión</h2>
    <form action="{{ route('login') }}" method="POST">
        @csrf

        <input type="email" name="correo" placeholder="Correo Electrónico" value="{{ old('correo') }}"
               class="w-full mb-3 px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-[#155f82]">
        <input type="password" name="password" placeholder="Contraseña"
               class="w-full mb-4 px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-[#155f82]">

        <button type="submit" class="w-full bg-[#155f82] text-white py-2 rounded hover:bg-[#104e6c]">Entrar</button>
    </form>

    <p class="text-center text-sm mt-4">
        ¿No tienes cuenta? <a href="{{ route('register') }}" class="text-[#155f82] underline">Regístrate</a>
    </p>
</div>
@endsection
