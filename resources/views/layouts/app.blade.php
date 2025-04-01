<!DOCTYPE html>
<html>
<head>
    <title>@yield('title', 'DocuGuard')</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    @yield('styles')
</head>
<body>
    @php
        use App\Enums\Rol;
    @endphp

    <nav class="bg-[#155f82] text-white shadow">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <h1 class="text-2xl font-bold">DocuGuard</h1>
            @auth
                <div class="flex gap-4 items-center">

                    <!-- Enlace visible para todos los usuarios -->
                    <a href="{{ route('documentos.index') }}" class="hover:underline text-sm">
                        Documentos
                    </a>
                    <a href="{{ route('graficos.index') }}" class="hover:underline text-sm">
                        Gr&aacute;ficos
                    </a>

                    <!-- Solo visible si el usuario es administrador -->
                    @if(Auth::user()->rol === Rol::ADMINISTRADOR->value)
                        <a href="{{ route('usuarios.index') }}" class="hover:underline text-sm">Usuarios</a>
                    @endif

                    <span class="text-sm">Hola, {{ Auth::user()->nombre }}</span>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="bg-white text-[#155f82] px-3 py-1 rounded hover:bg-gray-100">Cerrar sesión</button>
                    </form>
                </div>
            @endauth
        </div>
    </nav>

    <div class="container mt-4">
        @yield('content')
    </div>

    @yield('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
