@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="mb-10 text-center text-3xl font-extrabold text-[#155f82]">Reportes del Sistema</h1>

    @if(strtolower(auth()->user()->rol) === 'administrador')
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-8">

        {{-- Reporte 1: Documentos --}}
        <div class="bg-gradient-to-br from-[#e8f1f5] to-white p-6 rounded-2xl shadow-md border border-blue-100 hover:shadow-lg transition">
            <h2 class="font-bold text-xl text-[#155f82] mb-4 flex items-center gap-2">Documentos Cargados</h2>
            <div class="space-y-2">
                <a href="{{ route('reportes.documentos.csv') }}" class="w-full bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded shadow text-center block transition">Descargar CSV</a>
                <a href="{{ route('reportes.documentos.vista') }}" class="w-full bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-2 px-4 rounded shadow text-center block transition">Ver PDF (Imprimir)</a>
            </div>
        </div>

        {{-- Reporte 2: Permisos --}}
        <div class="bg-gradient-to-br from-[#e8f1f5] to-white p-6 rounded-2xl shadow-md border border-blue-100 hover:shadow-lg transition">
            <h2 class="font-bold text-xl text-[#155f82] mb-4 flex items-center gap-2">Permisos por Usuario</h2>
            <div class="space-y-2">
                <a href="{{ route('reportes.permisos.csv') }}" class="w-full bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded shadow text-center block transition">Descargar CSV</a>
                <a href="{{ route('reportes.permisos.vista') }}" class="w-full bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-2 px-4 rounded shadow text-center block transition">Ver PDF (Imprimir)</a>
            </div>
        </div>

        {{-- Reporte 3: Actividad --}}
        <div class="bg-gradient-to-br from-[#e8f1f5] to-white p-6 rounded-2xl shadow-md border border-blue-100 hover:shadow-lg transition">
            <h2 class="font-bold text-xl text-[#155f82] mb-4 flex items-center gap-2">Actividad de Usuarios</h2>
            <div class="space-y-2">
                <a href="{{ route('reportes.actividad.csv') }}" class="w-full bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded shadow text-center block transition">Descargar CSV</a>
                <a href="{{ route('reportes.actividad.vista') }}" class="w-full bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-2 px-4 rounded shadow text-center block transition">Ver PDF (Imprimir)</a>
            </div>
        </div>

        {{-- Reporte 4: Estado --}}
        <div class="bg-gradient-to-br from-[#e8f1f5] to-white p-6 rounded-2xl shadow-md border border-blue-100 hover:shadow-lg transition">
            <h2 class="font-bold text-xl text-[#155f82] mb-4 flex items-center gap-2">Documentos por Estado</h2>
            <div class="space-y-2">
                <a href="{{ route('reportes.estado.csv') }}" class="w-full bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded shadow text-center block transition">Descargar CSV</a>
                <a href="{{ route('reportes.estado.vista') }}" class="w-full bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-2 px-4 rounded shadow text-center block transition">Ver PDF (Imprimir)</a>
            </div>
        </div>

    </div>
    @else
        <p class="text-red-500 font-semibold mt-8 text-center">⚠️ Solo los administradores pueden acceder a estos reportes.</p>
    @endif
</div>
@endsection
