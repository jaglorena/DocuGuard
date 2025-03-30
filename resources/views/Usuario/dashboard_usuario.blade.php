@extends('Usuario.layouts.app')


@section('content')
<div class="bg-white p-8 rounded shadow max-w-3xl mx-auto">
    <h2 class="text-2xl font-bold text-[#155f82] mb-6">Panel de Usuario</h2>
    <p class="text-gray-700">Bienvenido, {{ Auth::user()->nombre }}. Aquí puedes ver tus documentos y permisos asignados.</p>
</div>
@endsection
