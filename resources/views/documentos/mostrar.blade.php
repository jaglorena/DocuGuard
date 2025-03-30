@extends('documentos.layouts.app')

@section('content')
<div class="container py-4">
    <div class="card p-4 shadow-sm">
        <h2>{{ $documento->titulo }}</h2>

        <p><strong>Descripción:</strong> {{ $documento->descripcion }}</p>
        <p><strong>Estado:</strong> 
            <span class="badge bg-{{ $documento->estado === 'publicado' ? 'success' : 'secondary' }}">
                {{ ucfirst($documento->estado) }}
            </span>
        </p>
        <p><strong>Versión:</strong> {{ $documento->version }}</p>

        <a href="{{ asset('storage/' . $documento->archivo) }}" class="btn btn-primary mt-3" target="_blank">
            Ver o Descargar Archivo
        </a>

        <hr class="my-4">

        <h5>📜 Historial de Versiones</h5>
        <ul class="list-group">
            @foreach($versiones as $ver)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    Versión {{ $ver->version }}
                    <small>{{ $ver->created_at->format('d/m/Y H:i') }}</small>
                </li>
            @endforeach
        </ul>
    </div>
</div>
@endsection
