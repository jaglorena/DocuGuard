<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>DocuGuard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#f8fafc] min-h-screen font-sans">

    <!-- Navbar -->
    <nav class="bg-[#155f82] text-white shadow">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <h1 class="text-2xl font-bold">DocuGuard</h1>
            @auth
            <div class="flex gap-4 items-center">
                <span class="text-sm">Hola, {{ Auth::user()->nombre }}</span>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="bg-white text-[#155f82] px-3 py-1 rounded hover:bg-gray-100">Cerrar sesión</button>
                </form>
            </div>
            @endauth
        </div>
    </nav>

    <main class="container mx-auto px-4 mt-10">
        @yield('content')
    </main>

</body>
</html>
