@extends('layouts.app')

@section('content')
    <h1>Detalle de Usuario</h1>
    <p><strong>Nombre:</strong> {{ $usuario->nombre }}</p>
    <p><strong>Email:</strong> {{ $usuario->correo }}</p>
@endsection
