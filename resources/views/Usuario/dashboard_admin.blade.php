@extends('Usuario.layouts.app')


@section('content')
<div class="bg-white p-8 rounded shadow max-w-4xl mx-auto">
    <h2 class="text-2xl font-bold text-[#155f82] mb-6">Panel de Administración</h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <a href="{{ route('usuarios.index') }}" class="bg-green-500 text-white p-4 rounded shadow hover:bg-green-600 transition">Ver Usuarios</a>
        <a href="{{ route('permisos.index') }}" class="bg-green-500 text-white p-4 rounded shadow hover:bg-green-600 transition">Ver Permisos</a>
        <a href="{{ route('documentos.index') }}" class="bg-purple-500 text-white p-4 rounded hover:bg-purple-600 transition">Gestión de Documentos</a>
    </div>
</div>
@endsection
